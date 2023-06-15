<?php

namespace App\Http\Controllers\Api\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\PurchaseRequestFilter;
use App\Http\Resources\PurchaseRequestResource;
use App\Http\Resources\PurchaseRequestItemResource;
use App\Http\Validations\PurchaseRequestValidation;
use App\Models\ConfigApproval;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestApproval;
use App\Models\PurchaseRequestItem;
use App\Models\PurchaseRequestStatus;
use App\Models\RequestQuotation;
use App\Models\User;
use App\Models\Vendor;
use App\Services\Notification as ServiceNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseOrderController extends Controller
{
    public function index(Request $request, $slug)
    {
        $validated = Validator::make($request->all(), PurchaseRequestValidation::index());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $purchaseRequests = PurchaseRequest::whereHas(
                                'purchaseRequestItem.requestQuotation.vendor',
                                function ($query) use ($slug) {
                                    $query->where('slug', $slug);
                                })
                                ->whereHas('purchaseRequestStatus', function($query) {
                                    $query->where('title', 'po released');
                                })
                                ->paginate($request->input('per_page', 10));

        return PurchaseRequestResource::collection($purchaseRequests);
    }


    public function all(Request $request, $slug)
    {
        $validated = Validator::make($request->all(), PurchaseRequestValidation::all());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        // Perlu diseragamkan return responsenya
        $purchaseRequests = PurchaseRequest::whereHas(
                                'purchaseRequestItem.requestQuotation.vendor',
                                function ($query) use ($slug) {
                                    $query->where('slug', $slug);
                                })
                                ->whereHas('purchaseRequestStatus', function($query) {
                                    $query->where('title', 'po released');
                                })
                                ->get();

        return PurchaseRequestResource::collection($purchaseRequests);
    }


    public function show(Request $request, $slug, $id)
    {

        $request['id'] = $id;

        $validated = Validator::make($request->all(), PurchaseRequestValidation::show());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $purchaseRequestItems = PurchaseRequestItem::where('purchase_request_id', $id)
                                    ->whereHas(
                                    'requestQuotation.vendor',
                                    function ($query) use ($slug) {
                                        $query->where('slug', $slug);
                                    })
                                    ->get();

        if ($purchaseRequestItems)
        {
            return PurchaseRequestItemResource::collection($purchaseRequestItems);
        }

        return $this->responseError([], 'Not found');
    }


    public function setApprove(Request $request, $slug, $id)
    {

        // $request['id'] = $id;

        // $validated = Validator::make($request->all(), PurchaseRequestValidation::show());

        // if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        foreach ($request->purchase_request_items as $item) {
            $purchaseRequestItem = PurchaseRequestItem::where('id', $item['id'])
                                    ->whereHas(
                                    'requestQuotation.vendor',
                                    function ($query) use ($slug) {
                                        $query->where('slug', $slug);
                                    })
                                    ->first();

            $purchaseRequestItem->winning_vendor_id         = $item['vendor_id'];
            $purchaseRequestItem->winning_vendor_price      = $item['vendor_price'];
            $purchaseRequestItem->winning_vendor_stock      = $item['vendor_stock'];
            $purchaseRequestItem->winning_vendor_incoterms  = $item['vendor_incoterms'];

            $purchaseRequestItem->save();
        }

        RequestQuotation::whereIn('id', $request->request_quotations)->update([ 'vendor_is_agree' => true ]);

        return $this->responseSuccess([], 'Approve is Success');
    }


    public function setReject(Request $request, $slug, $id)
    {

        // $request['id'] = $id;

        // $validated = Validator::make($request->all(), PurchaseRequestValidation::show());

        // if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $purchaseRequest = PurchaseRequestItem::where('id', $id)->first();

        if ($purchaseRequest)
        {
            $purchaseRequestStatus = PurchaseRequestStatus::where('title', 'waiting rfq approval')->first();

            $purchaseRequest->purchase_request_status_id   = $purchaseRequestStatus->id;

            $purchaseRequest->save();

            RequestQuotation::whereIn('id', $request->request_quotations)->update([ 'vendor_is_agree' => false ]);

            return $this->responseSuccess([], 'Reject is Success');
        }

        return $this->responseError([], 'Not found');
    }
}
