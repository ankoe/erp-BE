<?php

namespace App\Http\Controllers\Api\Office;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\PurchaseRequestFilter;
use App\Http\Resources\PurchaseRequestResource;
use App\Http\Validations\Office\PurchaseRequestValidation;
use App\Models\ConfigApproval;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestApproval;
use App\Models\PurchaseRequestApprovalHistory;
use App\Models\PurchaseRequestStatus;
use App\Models\User;
use App\Services\Notification as ServiceNotification;
use Carbon\Carbon;
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
                                ->where('company_id', $user->company->id)
                                ->whereHas('purchaseRequestStatus', function($query) {
                                    $query->whereNot('title', 'draft');
                                })
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

        $validated = Validator::make($request->all(), PurchaseRequestValidation::approval());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $roleId = $user->roles->pluck("id")->first();

        $purchaseRequest = PurchaseRequest::where(['id' => $id, 'company_id' => $user->company->id])->first();

        if ($purchaseRequest)
        {
            DB::beginTransaction();

            try {

                $statusTitle = null;

                if ($request->decision == 'approve') {

                    $statusTitle = 'waiting procurement approval';

                    $purchaseRequestApproval = PurchaseRequestApproval::where('purchase_request_id', $purchaseRequest->id)
                                                    ->where('role_id', $roleId)
                                                    ->first();

                    $purchaseRequestApproval->approve_user_id   = $user->id;
                    $purchaseRequestApproval->approved_at       = Carbon::now();

                    $purchaseRequestApproval->save();

                    // notif
                    (new ServiceNotification([$purchaseRequest->user]))->action(
                        'PR Approved',
                        'purchase-request-detail',
                        [ 'id' => $purchaseRequest->id ],
                        $purchaseRequest->code . ' approved by ' . $user->name
                    );

                } else {

                    $statusTitle = 'reject office approval';

                    PurchaseRequestApproval::where('purchase_request_id', $purchaseRequest->id)
                                                    ->update([
                                                        'approved_at'           => null,
                                                        'approve_user_id'       => null,
                                                    ]);

                    // notif
                    (new ServiceNotification([$purchaseRequest->user]))->action(
                        'PR Rejected',
                        'purchase-request-detail',
                        [ 'id' => $purchaseRequest->id ],
                        $purchaseRequest->code . ' rejected by' . $user->name
                    );

                }

                $purchaseRequestStatus = PurchaseRequestStatus::where('title', $statusTitle)->first();

                $purchaseRequest->purchase_request_status_id   = $purchaseRequestStatus->id;

                $purchaseRequest->save();

                PurchaseRequestApprovalHistory::create([
                    'purchase_request_id'   => $purchaseRequest->id,
                    'role_id'               => $roleId,
                    'user_id'               => $user->id,
                    'approved_at'           => Carbon::now(),
                    'approve_status'        => $request->decision,
                    'remarks'               => $request->remarks,
                ]);

                // notifikasi

                DB::commit();

                return $this->responseSuccess(new PurchaseRequestResource($purchaseRequest), $request->decision.' done');

            } catch(\Throwable $e) {

                DB::rollback();

                return $this->responseError([], $e->getMessage());
            }
        }

        return $this->responseError([], 'Not found');
    }
}
