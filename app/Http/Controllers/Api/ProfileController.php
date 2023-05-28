<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Validations\ProfileValidation;
use App\Mail\Auth\UserChangePasswordNotifMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show()
    {

        $profile = auth()->user();

        return $this->responseSuccess($profile, 'Detail Profile');
    }


    public function update(Request $request)
    {

        $validated = Validator::make($request->all(), ProfileValidation::update());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid', 201);

        $profile = auth()->user();

        $user = User::find($profile->id);

        if ($user)
        {

            if ( $request->hasFile('image_profile') )
            {
                if( !is_null($user->image_profile) ) Storage::delete($user->image_profile);
                $user->image_profile = $request->file('image_profile')->store('public/user');
            }

            $user->name     = $request->name;
            $user->mobile   = $request->mobile;

            $user->save();

            return $this->responseSuccess($user, 'Update profile');
        }

        return $this->responseError([], 'Not found');
    }


    public function passwordUpdate(Request $request)
    {

        $validated = Validator::make($request->all(), ProfileValidation::passwordUpdate());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid', 201);

        $profile = auth()->user();

        $result = auth()->attempt([
            'id'        => $profile->id,
            'password'  => $request->password_current
        ]);

        if (!$result) return $this->responseError([], 'Current password doesn\'t match');

        $user = User::find($profile->id);

        if ($user)
        {
            $user->password    = Hash::make($request->password);

            $user->save();

            Mail::to($user->email)->send(new UserChangePasswordNotifMail($user));

            return $this->responseSuccess($user, 'Update password');
        }

        return $this->responseError([], 'Not found');
    }

    public function imageUpdate(Request $request)
    {

        $validated = Validator::make($request->all(), ProfileValidation::imageUpdate());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid', 201);

        $profile = auth()->user();

        $user = User::find($profile->id);

        if ($user)
        {

            if ( !is_null($user->image_profile) ) Storage::delete($user->image_profile);
            $user->image_profile = $request->file('image_profile')->store('public/user');

            $user->save();

            return $this->responseSuccess($user, 'Image updated');
        }

        return $this->responseError([], 'Not found');
    }

     public function imageRemove(Request $request)
    {

        $profile = auth()->user();

        $user = User::find($profile->id);

        if ($user)
        {
            if ( !is_null($user->image_profile) ) Storage::delete($user->image_profile);

            $user->image_profile = null;

            $user->save();

            return $this->responseSuccess($user, 'Image removed');
        }

        return $this->responseError([], 'Not found');
    }
}
