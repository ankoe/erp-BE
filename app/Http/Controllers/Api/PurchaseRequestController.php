<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\PurchaseRequestFilter;
use App\Http\Resources\PurchaseRequestResource;
use App\Http\Validations\PurchaseRequestValidation;
use App\Models\ConfigApproval;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestApproval;
use App\Models\PurchaseRequestStatus;
use App\Models\User;
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

        $purchaseRequestStatus = PurchaseRequestStatus::where('title', 'draft')->first();

        $purchaseRequest = PurchaseRequest::create([
            'company_id'                    => $user->company->id,
            'user_id'                       => $user->id,
            'code'                          => 'PR001',
            'purchase_request_status_id'    => $purchaseRequestStatus->id,
        ]);

        return $this->responseSuccess($purchaseRequest, 'Add new account');
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


    public function apply($id)
    {

        $validated = Validator::make(['id' => $id], PurchaseRequestValidation::apply());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequest = PurchaseRequest::where(['id' => $id, 'company_id' => $user->company->id])->first();

        $purchaseRequestStatus = PurchaseRequestStatus::where('title', 'waiting office approval')->first();

        if ($purchaseRequest)
        {

            DB::beginTransaction();

            try {

                $purchaseRequest->purchase_request_status_id     = $purchaseRequestStatus->id;

                $purchaseRequest->save();

                $configApprovals = ConfigApproval::where('company_id', $user->company->id)->orderBy('order')->get();

                $firstRoleId = null;

                $bulkPurchaseRequestApproval = array();

                foreach ($configApprovals as $configApproval) {

                    if ($configApproval->order == 1) $firstRoleId = $configApproval->role_id;

                    array_push($bulkPurchaseRequestApproval, [
                        'purchase_request_id'   => $purchaseRequest->id,
                        'order'                 => $configApproval->order,
                        'role_id'               => $configApproval->role_id
                    ]);
                }

                PurchaseRequestApproval::insert($bulkPurchaseRequestApproval);

                // notifikasi belum jadi

                // $users = User::where('company_id', $user->company->id)->where('role_id', $firstRoleId)->get();

                // $bulkNotification = array();

                // foreach ($users as $user) {

                //     array_push($bulkPurchaseRequestApproval, [
                //         'order'     => $configApproval->order,
                //         'role_id'   => $configApproval->role_id
                //     ]);
                // }

                // Notification::insert($bulkNotification);

                DB::commit();

                return $this->responseSuccess(new PurchaseRequestResource($purchaseRequest), 'Update detail');

            } catch(\Throwable $e) {

                DB::rollback();

                return $this->responseError([], $e->getMessage());
            }
        }

        return $this->responseError([], 'Not found');
    }
}
