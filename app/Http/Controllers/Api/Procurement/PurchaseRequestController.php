<?php

namespace App\Http\Controllers\Api\Procurement;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\PurchaseRequestFilter;
use App\Http\Resources\PurchaseRequestResource;
use App\Http\Validations\PurchaseRequestValidation;
use App\Models\ConfigApproval;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\PurchaseRequestStatus;
use App\Models\User;
use App\Services\Notification as ServiceNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseRequestController extends Controller
{
    public function index(Request $request)
    {
        $validated = Validator::make($request->all(), PurchaseRequestValidation::index());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        $purchaseRequests = PurchaseRequest::filter(new PurchaseRequestFilter($request))
                                ->where('company_id', $user->company->id)
                                ->whereHas('purchaseRequestStatus', function($query) {
                                    $query->whereNot('title', 'draft');
                                })
                                ->paginate($request->input('per_page', 10));

        return PurchaseRequestResource::collection($purchaseRequests);
    }


    public function all(Request $request)
    {
        $validated = Validator::make($request->all(), PurchaseRequestValidation::all());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        // Perlu diseragamkan return responsenya
        $purchaseRequests = PurchaseRequest::filter(new PurchaseRequestFilter($request))
                                ->whereHas('purchaseRequestStatus', function($query) {
                                    $query->whereNot('title', 'draft');
                                })
                                ->where('company_id', $user->company->id)
                                ->get();

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


    public function approval(Request $request, $id)
    {
        $request['id'] = $id;

        // $validated = Validator::make($request->all(), PurchaseRequestValidation::approval());

        // if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequest = PurchaseRequest::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($purchaseRequest)
        {
            DB::beginTransaction();

            try {

                PurchaseRequestItem::where('purchase_request_id', $purchaseRequest->id)
                                        ->whereIn('id', $request->approve)
                                        ->update(['is_approve' => true]);

                PurchaseRequestItem::where('purchase_request_id', $purchaseRequest->id)
                                        ->whereIn('id', $request->reject)
                                        ->update(['is_approve' => false]);

                $purchaseRequestStatus = PurchaseRequestStatus::where('title', 'waiting rfq response')->first();

                $purchaseRequest->purchase_request_status_id   = $purchaseRequestStatus->id;

                $purchaseRequest->save();

                DB::commit();

                return $this->responseSuccess(new PurchaseRequestResource($purchaseRequest), 'procurement approval done');

            } catch(\Throwable $e) {

                DB::rollback();

                return $this->responseError([], $e->getMessage());
            }
        }

        return $this->responseError([], 'Not found');
    }
}
