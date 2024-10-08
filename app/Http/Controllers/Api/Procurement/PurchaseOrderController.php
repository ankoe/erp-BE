<?php

namespace App\Http\Controllers\Api\Procurement;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\PurchaseRequestFilter;
use App\Http\Resources\PurchaseRequestItemResource;
use App\Http\Validations\PurchaseRequestValidation;
use App\Models\ConfigApproval;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestApproval;
use App\Models\PurchaseRequestItem;
use App\Models\PurchaseRequestStatus;
use App\Models\User;
use App\Models\Vendor;
use App\Services\Notification as ServiceNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $validated = Validator::make($request->all(), PurchaseRequestValidation::index());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        $purchaseRequestItems = PurchaseRequestItem::filter(new PurchaseRequestFilter($request))
                                ->whereHas('purchaseRequest', function($q) use ($user) {
                                    $q->where('company_id', $user->company->id);
                                })
                                ->whereHas('purchaseRequestStatus', function($query) {
                                    $query->where('title', 'waiting po confirmation')
                                    ->orWhere('title', 'po released');
                                })
                                ->paginate($request->input('per_page', 10));

        return PurchaseRequestItemResource::collection($purchaseRequestItems);
    }


    public function all(Request $request)
    {
        $validated = Validator::make($request->all(), PurchaseRequestValidation::all());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        // Perlu diseragamkan return responsenya
        $purchaseRequestItems = PurchaseRequestItem::filter(new PurchaseRequestFilter($request))
                                ->whereHas('purchaseRequest', function($q) use ($user) {
                                    $q->where('company_id', $user->company->id);
                                })
                                ->whereHas('purchaseRequestStatus', function($query) {
                                    $query->where('title', 'waiting po confirmation')
                                    ->orWhere('title', 'po released');
                                })
                                ->get();

        return PurchaseRequestItemResource::collection($purchaseRequestItems);
    }


    public function show(Request $request, $id)
    {

        $request['id'] = $id;

        $validated = Validator::make($request->all(), PurchaseRequestValidation::show());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequestItem = PurchaseRequestItem::where('id', $id)
                                ->whereHas('purchaseRequest', function($q) use ($user) {
                                    $q->where('company_id', $user->company->id);
                                })->first();

        if ($purchaseRequestItem)
        {
            return $this->responseSuccess(new PurchaseRequestItemResource($purchaseRequestItem), 'Get detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function printPODocument(Request $request, $id, $vendorId)
    {
        // $request['id'] = $id;

        // $validated = Validator::make($request->all(), PurchaseRequestValidation::show());

        // if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $vendor = Vendor::where('id', $id)->first();

        $purchaseRequestItems = PurchaseRequestItem::where('purchase_request_id', $id)->where('winning_vendor_id', $vendorId)->get();


        if ($purchaseRequestItem)
        {
            $pdf = Pdf::loadView('document.purchase_order',
                [
                    'company'               => $user->company,
                    'vendor'                => $vendor,
                    'purchaseRequestItems'  => $purchaseRequestItems // seharusnya bisa pervendor dikumpulin jadi satu
                ]);

            return $pdf->stream('invoice.pdf');
        }

        return $this->responseError([], 'Not found');
    }
}
