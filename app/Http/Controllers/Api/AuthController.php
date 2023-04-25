<?php

namespace App\Http\Controllers\Api;

use App\Enums\RoleGroup;
use App\Enums\RoleUserDefault;
use App\Http\Controllers\Controller;
use App\Http\Validations\AuthValidation;
use App\Mail\Auth\UserRegisterTokenMail;
use App\Mail\Auth\UserResetPasswordMail;
use App\Mail\Auth\UserChangePasswordNotifMail;
use App\Models\Company;
use App\Models\ConfigApproval;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $validated = Validator::make($request->all(), AuthValidation::login());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        if (! $token = auth()->attempt([
                'email' => $request->email,
                'password' => $request->password,
                'is_active' => true
            ])
        ) {
            return $this->responseError([], 'Unauthorized');
        }

        $profile = auth()->user();

        if (is_null($profile->confirmed_at)) {
            return $this->responseError([], 'Account needs confirmation');
        }

        $profile->image_profile     = $profile->image_profile ? url(Storage::url($profile->image_profile)) : null;
        $profile->{'token'}         = $token;
        $profile->{'token_type'}    = 'bearer';
        $profile->{'expires_in'}    = auth()->factory()->getTTL() * 60;

        return $this->responseSuccess($profile, 'Login success');
    }


    public function register(Request $request)
    {

        $validated = Validator::make($request->all(), AuthValidation::register());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $company = Company::create([
            'name'  => $request->company
        ]);

        $role = Role::create([
            'name'          => $company->id . '_' . RoleGroup::Office . '_'.RoleUserDefault::Admin,
            'company_id'    => $company->id,
            'display_name'  => RoleUserDefault::Admin,
            'group'         => RoleGroup::Office,
            'is_default'    => true,
            'guard_name'    => 'api'
        ]);

        $user = User::create([
            'company_id'    => $company->id,
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'is_active'     => true
        ]);

        $user->assignRole([$role->id]);

        $this->generateActivationToken($user);

        Mail::to($user->email)->send(new UserRegisterTokenMail($user));

        return $this->responseSuccess($user, "An account has been created for {$request->email} successfully. Check your email to verify");
    }


    public function activationResend(Request $request)
    {

        $validated = Validator::make($request->all(), AuthValidation::activationResend());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->confirmed_at) return $this->responseError(null, 'User Not found');

        if ($user->email_proof_token && $user->updated_at >= Carbon::now()->subMinutes(5))
            return $this->responseError(null, 'Wait until 5 minutes for next request');

        $this->generateActivationToken($user);

        Mail::to($user->email)->send(new UserRegisterTokenMail($user));

        return $this->responseSuccess($user, 'Email Confirmation resend');
    }


    public function activationSubmit(Request $request)
    {
        $validated = Validator::make($request->all(), AuthValidation::activationSubmit());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = User::where(['email_proof_token' => $request->token])->first();

        if ($user && $user->email_proof_token_expires_at >= Carbon::now())
        {

            $user->email_proof_token              = null;
            $user->email_proof_token_expires_at   = null;
            $user->confirmed_at                   = Carbon::now();
            $user->save();

            // Begin create role for company

            $officeUser = Role::create([
                'name'          => $user->company->id.'_'.RoleGroup::Office.'_'.RoleUserDefault::User,
                'company_id'    => $user->company->id,
                'display_name'  => RoleUserDefault::User,
                'group'         => RoleGroup::Office,
                'is_default'    => true,
                'guard_name'    => 'api'
            ]);

            $officeSupervisor = Role::create([
                'name'          => $user->company->id.'_'.RoleGroup::Office.'_'.RoleUserDefault::Supervisor,
                'company_id'    => $user->company->id,
                'display_name'  => RoleUserDefault::Supervisor,
                'group'         => RoleGroup::Office,
                'is_default'    => true,
                'guard_name'    => 'api'
            ]);

            $procurementSupervisor = Role::create([
                'name'          => $user->company->id.'_'.RoleGroup::Procurement.'_'.RoleUserDefault::Supervisor,
                'company_id'    => $user->company->id,
                'display_name'  => RoleUserDefault::Supervisor,
                'group'         => RoleGroup::Procurement,
                'is_default'    => true,
                'guard_name'    => 'api'
            ]);

            foreach([$officeSupervisor, $procurementSupervisor] as $key => $role) {
                ConfigApproval::create([
                    'company_id'    => $user->company->id,
                    'role_id'       => $role->id,
                    'order'         => ++$key,
                ]);
            }

            return $this->responseSuccess($user, 'Confirmation Success');
        }

        return $this->responseError(null, 'The provided token is expired, invalid, or already used up');
    }


    public function passwordForgot(Request $request)
    {

        $length = 40;
        $token  = bin2hex(random_bytes($length));

        $validated = Validator::make($request->all(), AuthValidation::passwordForgot());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = User::where(['email' => $request->email])->first();

        if ($user)
        {
            if ($user->updated_at <= Carbon::now()->subMinutes(5))
            {
                $user->password_proof_token              = $token;
                $user->password_proof_token_expires_at   = Carbon::now()->addDay();
                $user->save();

                Mail::to($user->email)->send(new UserResetPasswordMail($user));

                return $this->responseSuccess($user, 'Link for reset password has been send');
            }

            return $this->responseError(null, 'Wait until 5 minutes for next request');
        }

        return $this->responseError(null, 'Email Not found');
    }


    public function passwordReset(Request $request)
    {

        $validated = Validator::make($request->all(), AuthValidation::passwordReset());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = User::where(['password_proof_token' => $request->token])->first();

        if ($user && $user->password_proof_token_expires_at >= Carbon::now())
        {

            $user->password_proof_token              = null;
            $user->password_proof_token_expires_at   = null;
            $user->password                          = Hash::make($request->password);
            $user->save();

            Mail::to($user->email)->send(new UserChangePasswordNotifMail($user));

            return $this->responseSuccess($user, 'Password Change Success');
        }

        return $this->responseError(null, 'The password token is expired, invalid, or already used up');

    }


    public function refresh()
    {
        $data = array(
            'token'         => auth()->refresh(),
            'token_type'    => 'bearer',
            'expires_in'    => auth()->factory()->getTTL() * 60
        );

        return $this->responseSuccess($data, 'New token created');
    }


    private function generateActivationToken(User $user)
    {
        $length = 25;
        $token  = bin2hex(random_bytes($length));
        $user->email_proof_token             = $token;
        $user->email_proof_token_expires_at  = Carbon::now()->addHours(config('variable.limit.token'));
        $user->save();
    }
}
