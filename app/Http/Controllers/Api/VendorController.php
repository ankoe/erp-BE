<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\VendorFilter;
use App\Http\Resources\VendorResource;
use App\Http\Validations\VendorValidation;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $validated = Validator::make($request->all(), VendorValidation::index());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        $vendors = Vendor::filter(new VendorFilter($request))->where('company_id', $user->company->id)->paginate($request->input('per_page', 10));

        return VendorResource::collection($vendors);
    }


    public function all(Request $request)
    {
        $validated = Validator::make($request->all(), VendorValidation::all());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        // Perlu diseragamkan return responsenya
        $vendors = Vendor::filter(new VendorFilter($request))->where('company_id', $user->company->id)->get();

        return VendorResource::collection($vendors);
    }


    public function show(Request $request, $id)
    {

        $request['id'] = $id;

        $validated = Validator::make($request->all(), VendorValidation::show());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $vendor = Vendor::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($vendor)
        {
            return $this->responseSuccess(new VendorResource($vendor), 'Get detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function store(Request $request)
    {

        $validated = Validator::make($request->all(), VendorValidation::store());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $vendor = Vendor::create([
            'company_id'         => $user->company->id,
            'name'              => $request->name,
            'material_category_id'              => $request->material_category_id,
        ]);

        return $this->responseSuccess($vendor, 'Add new account');
    }


    public function update(Request $request, $id)
    {
        $request['id'] = $id;

        $validated = Validator::make($request->all(), VendorValidation::update());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $vendor = Vendor::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($vendor)
        {
            $vendor->name                 = $request->name;
            $vendor->material_category_id                 = $request->material_category_id;

            $vendor->save();

            return $this->responseSuccess(new VendorResource($vendor), 'Update detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function destroy($id)
    {

        $validated = Validator::make(['id' => $id], VendorValidation::destroy());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $vendor = Vendor::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($vendor)
        {
            // income dijadikan null atau di ubah ke kategori lainnya

            $vendor->delete();

            return $this->responseSuccess($vendor, 'Delete Record', 204);
        }

        return $this->responseError([], 'Not found');
    }
}
