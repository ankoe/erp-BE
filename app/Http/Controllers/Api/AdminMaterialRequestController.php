<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\MaterialRequestFilter;
use App\Http\Resources\MaterialRequestResource;
use App\Http\Validations\AdminMaterialRequestValidation;
use App\Models\Material;
use App\Models\MaterialRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminMaterialRequestController extends Controller
{
    public function index(Request $request)
    {
        $validated = Validator::make($request->all(), AdminMaterialRequestValidation::index());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        $materialRequests = MaterialRequest::filter(new MaterialRequestFilter($request))
                                ->where('company_id', $user->company->id)
                                ->orderBy('created_at', 'desc')
                                ->paginate($request->input('per_page', 10));

        return MaterialRequestResource::collection($materialRequests);
    }


    public function show(Request $request, $id)
    {

        $request['id'] = $id;

        $validated = Validator::make($request->all(), AdminMaterialRequestValidation::show());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $materialRequest = MaterialRequest::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($materialRequest)
        {
            return $this->responseSuccess(new MaterialRequestResource($materialRequest), 'Get material request detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function approve(Request $request,$id)
    {
        $request['id'] = $id;

        $validated = Validator::make($request->all(), AdminMaterialRequestValidation::approve());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $materialRequest = MaterialRequest::where(['id' => $id, 'company_id' => $user->company->id])->first();

        $attachment = null;

        if ( $request->hasFile('attachment') )
        {
            Storage::delete($materialRequest->attachment);
            $attachment = $request->file('attachment')->store('public/material');
        } else {
            $attachment = $materialRequest->attachment;
        }

        $material = Material::Create([
            'company_id'            => $user->company->id,
            'material_category_id'  => $request->material_category_id,
            'name'                  => $request->name,
            'number'                => $request->number,
            'description'           => $request->description,
            'unit_id'               => $request->unit_id,
            'price'                 => $request->price,
            'stock'                 => $request->stock,
            'attachment'            => $attachment,
        ]);

        $materialRequest->delete();

        return $this->responseSuccess(new MaterialRequestResource($material), 'Approval material');
    }


    public function reject($id)
    {

        $validated = Validator::make(['id' => $id], AdminMaterialRequestValidation::reject());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $materialRequest = MaterialRequest::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($materialRequest)
        {
            $materialRequest->delete();

            return $this->responseSuccess($materialRequest, 'Delete material request', 204);
        }

        return $this->responseError([], 'Not found');
    }
}
