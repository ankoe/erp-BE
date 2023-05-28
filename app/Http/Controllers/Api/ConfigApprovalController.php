<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\ConfigApprovalFilter;
use App\Http\Resources\ConfigApprovalResource;
use App\Http\Validations\ConfigApprovalValidation;
use App\Models\ConfigApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigApprovalController extends Controller
{
    public function index(Request $request)
    {
        $validated = Validator::make($request->all(), ConfigApprovalValidation::index());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        $configApprovals = ConfigApproval::filter(new ConfigApprovalFilter($request))
                                ->where('company_id', $user->company->id)
                                ->get();

        return ConfigApprovalResource::collection($configApprovals);
    }


    public function store(Request $request)
    {

        $validated = Validator::make($request->all(), ConfigApprovalValidation::store());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $configApproval = ConfigApproval::Create([
            'company_id'      => $user->company->id,
            'user_id'         => $request->user_id,
            'order'           => 99, // set paling terakhir
        ]);

        return $this->responseSuccess($configApproval, 'Add new account');
    }


    public function destroy($id)
    {

        $validated = Validator::make(['id' => $id], ConfigApprovalValidation::destroy());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $configApproval = ConfigApproval::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($configApproval)
        {
            $configApproval->delete();

            return $this->responseSuccess($configApproval, 'Delete Record', 204);
        }

        return $this->responseError([], 'Not found');
    }


    public function sort(Request $request)
    {
        $validated = Validator::make($request->all(), ConfigApprovalValidation::sort());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $arrayApprovalId = [];

        foreach ($request->approvals as $approval) {
            $configApproval = ConfigApproval::updateOrCreate(
                                [
                                    'company_id'    =>  $user->company->id,
                                    'role_id'       =>  $approval['role_id']
                                ],
                                [
                                    'order'     => $approval['order']
                                ]
                            );

            array_push($arrayApprovalId, $configApproval->id);
        }

        ConfigApproval::where('company_id', $user->company_id)->whereNotIn('id', $arrayApprovalId)->delete();

        return $this->responseSuccess([], 'sort success');
    }
}
