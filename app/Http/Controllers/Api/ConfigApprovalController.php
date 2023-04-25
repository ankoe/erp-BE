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

        $configApprovals = ConfigApproval::filter(new ConfigApprovalFilter($request))->where('company_id', $user->company->id)->paginate($request->input('per_page', 10));

        return ConfigApprovalResource::collection($configApprovals);
    }


    public function all(Request $request)
    {
        $validated = Validator::make($request->all(), ConfigApprovalValidation::all());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        // Perlu diseragamkan return responsenya
        $configApprovals = ConfigApproval::filter(new ConfigApprovalFilter($request))->where('company_id', $user->company->id)->get();

        return ConfigApprovalResource::collection($configApprovals);
    }


    public function show(Request $request, $id)
    {

        $request['id'] = $id;

        $validated = Validator::make($request->all(), ConfigApprovalValidation::show());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $configApproval = ConfigApproval::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($configApproval)
        {
            return $this->responseSuccess(new ConfigApprovalResource($configApproval), 'Get detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function store(Request $request)
    {

        $validated = Validator::make($request->all(), ConfigApprovalValidation::store());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $configApproval = ConfigApproval::Create([
            'company_id'         => $user->company->id,
            'user_id'         => $request->user_id,
            'order'           => $request->order,
        ]);

        return $this->responseSuccess($configApproval, 'Add new account');
    }


    public function update(Request $request, $id)
    {
        $request['id'] = $id;

        $validated = Validator::make($request->all(), ConfigApprovalValidation::update());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $configApproval = ConfigApproval::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($configApproval)
        {
            $configApproval->user_id      = $request->user_id;
            $configApproval->order      = $request->order;

            $configApproval->save();

            return $this->responseSuccess(new ConfigApprovalResource($configApproval), 'Update detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function destroy($id)
    {

        $validated = Validator::make(['id' => $id], ConfigApprovalValidation::destroy());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $configApproval = ConfigApproval::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($configApproval)
        {
            // income dijadikan null atau di ubah ke kategori lainnya

            $configApproval->delete();

            return $this->responseSuccess($configApproval, 'Delete Record', 204);
        }

        return $this->responseError([], 'Not found');
    }
}
