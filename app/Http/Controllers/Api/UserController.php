<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\UserFilter;
use App\Http\Resources\UserResource;
use App\Http\Validations\UserValidation;
use App\Mail\User\UserPasswordTemporaryMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $validated = Validator::make($request->all(), UserValidation::index());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $admin = auth()->user();

        $users = User::filter(new UserFilter($request))->where('company_id', $admin->company->id)->paginate($request->input('per_page', 10));

        return UserResource::collection($users);
    }


    public function all(Request $request)
    {
        $validated = Validator::make($request->all(), UserValidation::all());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $admin = auth()->user();

        // Perlu diseragamkan return responsenya
        $users = User::filter(new UserFilter($request))->where('company_id', $admin->company->id)->get();

        return UserResource::collection($users);
    }


    public function show(Request $request, $id)
    {

        $request['id'] = $id;

        $validated = Validator::make($request->all(), UserValidation::show());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $admin = auth()->user();

        $user = User::where(['id' => $id, 'company_id' => $admin->company->id])->first();

        if ($user)
        {
            return $this->responseSuccess(new UserResource($user), 'Get user detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function store(Request $request)
    {

        $validated = Validator::make($request->all(), UserValidation::store());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $admin = auth()->user();

        $passwordTemporary = Str::random(8);

        $user = User::create([
            'company_id'    => $admin->company->id,
            'name'          => $request->name,
            'email'         => $request->email,
            'mobile'        => $request->mobile,
            'password'      => Hash::make($passwordTemporary),
            'confirmed_at'  => Carbon::now(),
            'is_active'     => true,
        ]);

        $user->assignRole($request->role_id);

        Mail::to($user->email)->send(new UserPasswordTemporaryMail($user, $passwordTemporary));

        return $this->responseSuccess($user, 'Add new user');
    }


    public function update(Request $request, $id)
    {
        $request['id'] = $id;

        $validated = Validator::make($request->all(), UserValidation::update($request->id));

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $admin = auth()->user();

        $user = User::where(['id' => $id, 'company_id' => $admin->company->id])->first();

        if ($user)
        {
            $user->name    = $request->name;
            $user->email    = $request->email;
            $user->mobile    = $request->mobile;
            $user->is_active    = $request->is_active;

            $user->save();

            $user->syncRoles($request->role_id);

            return $this->responseSuccess(new UserResource($user), 'Update user');
        }

        return $this->responseError([], 'Not found');
    }


    public function destroy($id)
    {

        $validated = Validator::make(['id' => $id], UserValidation::destroy());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $admin = auth()->user();

        $user = User::where(['id' => $id, 'company_id' => $admin->company->id])->first();

        if ($user)
        {
            // income dijadikan null atau di ubah ke kategori lainnya

            $user->delete();

            return $this->responseSuccess($user, 'Delete user', 204);
        }

        return $this->responseError([], 'Not found');
    }
}
