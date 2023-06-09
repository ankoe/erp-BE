<?php

namespace App\Http\Controllers\Api\Procurement;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\PurchaseRequestFilter;
use App\Http\Resources\RequestForQuotationResource;
use App\Http\Validations\PurchaseRequestValidation;
use App\Mail\Approval\VendorRFQAccessMail;
use App\Models\ConfigApproval;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestApproval;
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
    public function index(Request $request)
    {
        $validated = Validator::make($request->all(), PurchaseRequestValidation::index());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        $purchaseRequests = PurchaseRequest::filter(new PurchaseRequestFilter($request))
                                ->where('company_id', $user->company->id)
                                ->whereHas('purchaseRequestStatus', function($query) {
                                    $query->where('title', 'waiting rfq response')
                                    ->orWhere('title', 'waiting rfq approval')
                                    ->orWhere('title', 'waiting po confirmation');

                                })
                                ->paginate($request->input('per_page', 10));

        return RequestForQuotationResource::collection($purchaseRequests);
    }


    public function all(Request $request)
    {
        $validated = Validator::make($request->all(), PurchaseRequestValidation::all());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        // Perlu diseragamkan return responsenya
        $purchaseRequests = PurchaseRequest::filter(new PurchaseRequestFilter($request))
                                ->where('company_id', $user->company->id)
                                ->whereHas('purchaseRequestStatus', function($query) {
                                    $query->where('title', 'waiting rfq response')
                                    ->orWhere('title', 'waiting rfq approval')
                                    ->orWhere('title', 'waiting po confirmation');
                                })
                                ->get();

        return RequestForQuotationResource::collection($purchaseRequests);
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
            return $this->responseSuccess(new RequestForQuotationResource($purchaseRequest), 'Get detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function proposeVendor(Request $request, $id)
    {
        // $request['id'] = $id;

        // // $validated = Validator::make($request->all(), PurchaseRequestValidation::approval());

        // // if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequest = PurchaseRequest::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($purchaseRequest)
        {
            DB::beginTransaction();

            try {
                $vendors = [];
                $bulkRequestQuotation = [];
                foreach ($request->items as $item) {
                    foreach ($item['vendors'] as $vendor) {
                        array_push($bulkRequestQuotation, [
                            'company_id' => $user->company->id,
                            'purchase_request_item_id' => $item['id'],
                            'vendor_id' => $vendor,
                            'created_at' => Carbon::now(),
                        ]);

                        array_push($vendors, $vendor);
                    }
                }

                RequestQuotation::insert($bulkRequestQuotation);

                $vendors = Vendor::where('company_id', $user->company->id)->whereIn('id', array_unique($vendors))->get();

                // kirim email
                foreach ($vendors as $vendor) {
                    Mail::to($vendor->email)->send(new VendorRFQAccessMail($vendor));
                }

                // ganti status

                $purchaseRequestStatus = PurchaseRequestStatus::where('title', 'waiting rfq approval')->first();

                $purchaseRequest->purchase_request_status_id   = $purchaseRequestStatus->id;

                $purchaseRequest->save();

                DB::commit();

                return $this->responseSuccess(new RequestForQuotationResource($purchaseRequest), 'procurement approval done');

            } catch(\Throwable $e) {

                DB::rollback();

                return $this->responseError([], $e->getMessage());
            }
        }

        return $this->responseError([], 'Not found');
    }


    public function proposeApproval(Request $request, $id)
    {
        // $request['id'] = $id;

        // // $validated = Validator::make($request->all(), PurchaseRequestValidation::approval());

        // // if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequest = PurchaseRequest::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($purchaseRequest)
        {
            DB::beginTransaction();

            try {
                $bulkPurchaseRequestItemId = [];
                $bulkRequestQuotationId = [];
                foreach ($request->items as $item) {
                    array_push($bulkPurchaseRequestItemId, $item['id']);
                    array_push($bulkRequestQuotationId, $item['id']);
                }

                RequestQuotation::whereIn('purchase_request_item_id', $bulkPurchaseRequestItemId)->update(['is_selected' => false]);
                RequestQuotation::whereIn('id', $bulkRequestQuotationId)->update(['is_selected' => true]);

                // ganti status

                $purchaseRequestStatus = PurchaseRequestStatus::where('title', 'waiting po confirmation')->first();

                $purchaseRequest->purchase_request_status_id   = $purchaseRequestStatus->id;

                $purchaseRequest->save();

                DB::commit();

                return $this->responseSuccess(new RequestForQuotationResource($purchaseRequest), 'procurement approval done');

            } catch(\Throwable $e) {

                DB::rollback();

                return $this->responseError([], $e->getMessage());
            }
        }

        return $this->responseError([], 'Not found');
        // saat klik link di email si vendor akan menampilkan per rfq lalu menampilkan detail dia
    }


    public function setApprove(Request $request, $id)
    {
        // $request['id'] = $id;

        // // $validated = Validator::make($request->all(), PurchaseRequestValidation::approval());

        // // if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequest = PurchaseRequest::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($purchaseRequest)
        {
            DB::beginTransaction();

            try {

                // ganti status

                $purchaseRequestStatus = PurchaseRequestStatus::where('title', 'po released')->first();

                $purchaseRequest->purchase_request_status_id   = $purchaseRequestStatus->id;

                $purchaseRequest->save();

                DB::commit();

                return $this->responseSuccess(new RequestForQuotationResource($purchaseRequest), 'procurement approval done');

            } catch(\Throwable $e) {

                DB::rollback();

                return $this->responseError([], $e->getMessage());
            }
        }

        return $this->responseError([], 'Not found');
        // saat klik link di email si vendor akan menampilkan per rfq lalu menampilkan detail dia
    }


    public function setReject(Request $request, $id)
    {
        // $request['id'] = $id;

        // // $validated = Validator::make($request->all(), PurchaseRequestValidation::approval());

        // // if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequest = PurchaseRequest::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($purchaseRequest)
        {
            DB::beginTransaction();

            try {

                // ganti status

                $purchaseRequestStatus = PurchaseRequestStatus::where('title', 'po released')->first();

                $purchaseRequest->purchase_request_status_id   = $purchaseRequestStatus->id;

                $purchaseRequest->save();

                DB::commit();

                return $this->responseSuccess(new RequestForQuotationResource($purchaseRequest), 'procurement approval done');

            } catch(\Throwable $e) {

                DB::rollback();

                return $this->responseError([], $e->getMessage());
            }
        }

        return $this->responseError([], 'Not found');
        // saat klik link di email si vendor akan menampilkan per rfq lalu menampilkan detail dia
    }
}
