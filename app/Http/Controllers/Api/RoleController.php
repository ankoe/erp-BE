<?php

namespace App\Http\Controllers\Api;

use App\Enums\PermissionType;
use App\Enums\RoleGroup;
use App\Http\Controllers\Controller;
use App\Http\Filters\Api\RoleFilter;
use App\Http\Resources\RoleResource;
use App\Http\Validations\RoleValidation;
use App\Models\Permission;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $validated = Validator::make($request->all(), RoleValidation::index());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        $roles = Role::filter(new RoleFilter($request))->where('company_id', $user->company->id)->paginate($request->input('per_page', 10));

        return RoleResource::collection($roles);
    }


    public function all(Request $request)
    {
        $validated = Validator::make($request->all(), RoleValidation::all());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        // Perlu diseragamkan return responsenya
        $roles = Role::filter(new RoleFilter($request))->where('company_id', $user->company->id)->get();

        return RoleResource::collection($roles);
    }


    public function show(Request $request, $id)
    {

        $request['id'] = $id;

        $validated = Validator::make($request->all(), RoleValidation::show());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $role = Role::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($role)
        {
            return $this->responseSuccess(new RoleResource($role), 'Get role detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function store(Request $request)
    {

        $validated = Validator::make($request->all(), RoleValidation::store());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $role = Role::create([
            'company_id'         => $user->company->id,
            'name'           => $user->company->id.'_'.$request->group.'_'.str_replace(' ','-', $request->name),
            'display_name'  => $request->name,
            'group'         => $request->group,
            'guard_name'    => 'api'
        ]);

        $permissions = null;

        if ($request->group == RoleGroup::Office) {
            $permissions = Permission::whereIn('name',
                                                [ PermissionType::OfficePurchaseRequest ]
                                            )->get();

        } else {
            $permissions = Permission::whereIn('name',
                            [
                                PermissionType::ProcurementPurchaseOrder,
                                PermissionType::ProcurementRFQ,
                                PermissionType::ProcurementRFQApproval,
                                PermissionType::ProcurementPO,
                                PermissionType::ProcurementMessage,
                            ])->get();
        }

        $role->syncPermissions($permissions);

        return $this->responseSuccess($role, 'Create new role');
    }


    public function update(Request $request, $id)
    {
        $request['id'] = $id;

        $validated = Validator::make($request->all(), RoleValidation::update());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $role = Role::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($role)
        {
            if ($role->is_default) return $this->responseError([], 'Peran default tidak bisa diubah');

            $role->name             = $user->company->id.'_'.$request->group.'_'.str_replace(' ','-', $request->name);
            $role->display_name     = $request->name;
            $role->group            = $request->group;

            $role->save();

            $permissions = null;

            if ($request->group == RoleGroup::Office) {
                $permissions = Permission::whereIn('name',
                                                    [ PermissionType::OfficePurchaseRequest ]
                                                )->get();

            } else {
                $permissions = Permission::whereIn('name',
                                [
                                    PermissionType::ProcurementPurchaseOrder,
                                    PermissionType::ProcurementRFQ,
                                    PermissionType::ProcurementPO,
                                    PermissionType::ProcurementMessage,
                                ])->get();
            }

            $role->syncPermissions($permissions);

            return $this->responseSuccess(new RoleResource($role), 'Update role');
        }

        return $this->responseError([], 'Not found');
    }


    public function destroy($id)
    {

        $validated = Validator::make(['id' => $id], RoleValidation::destroy());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $role = Role::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($role)
        {
            // income dijadikan null atau di ubah ke kategori lainnya

            $role->delete();

            return $this->responseSuccess($role, 'Delete role', 204);
        }

        return $this->responseError([], 'Not found');
    }
}
