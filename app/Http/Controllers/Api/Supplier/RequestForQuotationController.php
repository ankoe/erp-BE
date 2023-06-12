<?php

namespace App\Http\Controllers\Api\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\PurchaseRequestFilter;
use App\Http\Resources\PurchaseRequestItemResource;
use App\Http\Resources\RequestForQuotationResource;
use App\Http\Validations\PurchaseRequestValidation;
use App\Mail\Approval\VendorRFQAccessMail;
use App\Models\ConfigApproval;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestApproval;
use App\Models\PurchaseRequestItem;
use App\Models\PurchaseRequestStatus;
use App\Models\RequestQuotation;
use App\Models\User;
use App\Models\Vendor;
use App\Services\Notification as ServiceNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RequestForQuotationController extends Controller
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
                                    $query->where('title', 'waiting rfq response')
                                    ->orWhere('title', 'waiting rfq approval')
                                    ->orWhere('title', 'waiting po confirmation');
                                })
                                ->paginate($request->input('per_page', 10));

        return RequestForQuotationResource::collection($purchaseRequests);
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
                                    $query->where('title', 'waiting rfq response')
                                    ->orWhere('title', 'waiting rfq approval')
                                    ->orWhere('title', 'waiting po confirmation');
                                })
                                ->get();

        return RequestForQuotationResource::collection($purchaseRequests);
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
}
