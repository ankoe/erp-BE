<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\BranchFilter;
use App\Http\Resources\BranchResource;
use App\Http\Validations\BranchValidation;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $validated = Validator::make($request->all(), BranchValidation::index());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        $branchs = Branch::filter(new BranchFilter($request))->where('company_id', $user->company->id)->paginate($request->input('per_page', 10));

        return BranchResource::collection($branchs);
    }


    public function all(Request $request)
    {
        $validated = Validator::make($request->all(), BranchValidation::all());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        // Perlu diseragamkan return responsenya
        $branchs = Branch::filter(new BranchFilter($request))->where('company_id', $user->company->id)->get();

        return BranchResource::collection($branchs);
    }


    public function show(Request $request, $id)
    {

        $request['id'] = $id;

        $validated = Validator::make($request->all(), BranchValidation::show());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $branch = Branch::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($branch)
        {
            return $this->responseSuccess(new BranchResource($branch), 'Get detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function store(Request $request)
    {

        $validated = Validator::make($request->all(), BranchValidation::store());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $branch = Branch::Create([
            'company_id'    => $user->company->id,
            'name'          => $request->name,
            'address'         => $request->address,
            'email'          => $request->email,
            'mobile'         => $request->mobile,
        ]);

        return $this->responseSuccess($branch, 'Add new location');
    }


    public function update(Request $request, $id)
    {
        $request['id'] = $id;

        $validated = Validator::make($request->all(), BranchValidation::update());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $branch = Branch::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($branch)
        {
            $branch->name   = $request->name;
            $branch->address   = $request->address;
            $branch->email   = $request->email;
            $branch->mobile   = $request->mobile;

            $branch->save();

            return $this->responseSuccess(new BranchResource($branch), 'Update detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function destroy($id)
    {

        $validated = Validator::make(['id' => $id], BranchValidation::destroy());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $branch = Branch::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($branch)
        {

            $branch->delete();

            return $this->responseSuccess($branch, 'Delete Record', 204);
        }

        return $this->responseError([], 'Not found');
    }
}
