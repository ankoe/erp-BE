<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\PurchaseRequestFilter;
use App\Http\Resources\PurchaseRequestResource;
use App\Http\Validations\PurchaseRequestValidation;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseRequestController extends Controller
{
    public function index(Request $request)
    {
        $validated = Validator::make($request->all(), PurchaseRequestValidation::index());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        $purchaseRequests = PurchaseRequest::filter(new PurchaseRequestFilter($request))->where('company_id', $user->company->id)->paginate($request->input('per_page', 10));

        return PurchaseRequestResource::collection($purchaseRequests);
    }


    public function all(Request $request)
    {
        $validated = Validator::make($request->all(), PurchaseRequestValidation::all());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        // Perlu diseragamkan return responsenya
        $purchaseRequests = PurchaseRequest::filter(new PurchaseRequestFilter($request))->where('company_id', $user->company->id)->get();

        return PurchaseRequestResource::collection($purchaseRequests);
    }


    public function show(Request $request, $id)
    {

        $request['id'] = $id;

        $validated = Validator::make($request->all(), PurchaseRequestValidation::show());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequest = PurchaseRequest::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($purchaseRequest)
        {
            return $this->responseSuccess(new PurchaseRequestResource($purchaseRequest), 'Get detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function store(Request $request)
    {

        $validated = Validator::make($request->all(), PurchaseRequestValidation::store());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequest = PurchaseRequest::create([
            'company_id'         => $user->company->id,
            'user_id'         => $user->id,
            'status'           => $request->status,
        ]);

        return $this->responseSuccess($purchaseRequest, 'Add new account');
    }


    public function update(Request $request, $id)
    {
        $request['id'] = $id;

        $validated = Validator::make($request->all(), PurchaseRequestValidation::update());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequest = PurchaseRequest::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($purchaseRequest)
        {
            $purchaseRequest->status     = $request->status;

            $purchaseRequest->save();

            return $this->responseSuccess(new PurchaseRequestResource($purchaseRequest), 'Update detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function destroy($id)
    {

        $validated = Validator::make(['id' => $id], PurchaseRequestValidation::destroy());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequest = PurchaseRequest::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($purchaseRequest)
        {
            // income dijadikan null atau di ubah ke kategori lainnya

            $purchaseRequest->delete();

            return $this->responseSuccess($purchaseRequest, 'Delete Record', 204);
        }

        return $this->responseError([], 'Not found');
    }
}
