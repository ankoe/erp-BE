<?php

namespace App\Http\Controllers\Api;

use App\Http\Filters\Api\PurchaseRequestApprovalFilter;
use App\Http\Resources\PurchaseRequestApprovalResource;
use App\Http\Validations\PurchaseRequestApprovalValidation;
use App\Models\PurchaseRequestApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseRequestApprovalController extends Controller
{
    public function index(Request $request)
    {
        $validated = Validator::make($request->all(), PurchaseRequestApprovalValidation::index());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        $purchaseRequestApprovals = PurchaseRequestApproval::filter(new PurchaseRequestApprovalFilter($request))
                // ->where('user_id', $user->id)
                ->paginate($request->input('per_page', 10));

        return PurchaseRequestApprovalResource::collection($purchaseRequestApprovals);
    }


    public function all(Request $request)
    {
        $validated = Validator::make($request->all(), PurchaseRequestApprovalValidation::all());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        // Perlu diseragamkan return responsenya
        $purchaseRequestApprovals = PurchaseRequestApproval::filter(new PurchaseRequestApprovalFilter($request))
                // ->where('user_id', $user->id)
                ->get();

        return PurchaseRequestApprovalResource::collection($purchaseRequestApprovals);
    }


    public function show(Request $request, $id)
    {

        $request['id'] = $id;

        $validated = Validator::make($request->all(), PurchaseRequestApprovalValidation::show());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequestApproval = PurchaseRequestApproval::where('id', $id)
                // ->where('user_id', $user->id)
                ->first();

        if ($purchaseRequestApproval)
        {
            return $this->responseSuccess(new PurchaseRequestApprovalResource($purchaseRequestApproval), 'Get detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function store(Request $request)
    {

        $validated = Validator::make($request->all(), PurchaseRequestApprovalValidation::store());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequestApproval = PurchaseRequestApproval::create([
            'purchase_request_id'         => $request->purchase_request_id,
            'material_id'           => $request->material_id,
            'price'         => $request->price,
            'description'           => $request->description,
            'quantity'         => $request->quantity,
            'total'           => $request->total,
            'vendor_id'         => $request->vendor_id,
            'branch_id'           => $request->branch_id,
            'expected_at'           => $request->expected_at,
            'file'           => $request->file,
        ]);

        return $this->responseSuccess($purchaseRequestApproval, 'Add new account');
    }


    public function update(Request $request, $id)
    {
        $request['id'] = $id;

        $validated = Validator::make($request->all(), PurchaseRequestApprovalValidation::update());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequestApproval = PurchaseRequestApproval::where('id', $id)
                // ->where('user_id', $user->id)
                ->first();

        if ($purchaseRequestApproval)
        {
            $purchaseRequestApproval->material_id     = $request->material_id;
            $purchaseRequestApproval->price     = $request->price;
            $purchaseRequestApproval->description     = $request->description;
            $purchaseRequestApproval->quantity     = $request->quantity;
            $purchaseRequestApproval->total     = $request->total;
            $purchaseRequestApproval->vendor_id     = $request->vendor_id;
            $purchaseRequestApproval->branch_id     = $request->branch_id;
            $purchaseRequestApproval->expected_at     = $request->expected_at;
            $purchaseRequestApproval->file     = $request->file;

            $purchaseRequestApproval->save();

            return $this->responseSuccess(new PurchaseRequestApprovalResource($purchaseRequestApproval), 'Update detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function destroy($id)
    {

        $validated = Validator::make(['id' => $id], PurchaseRequestApprovalValidation::destroy());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequestApproval = PurchaseRequestApproval::where('id', $id)
                // ->where('user_id', $user->id)
                ->first();

        if ($purchaseRequestApproval)
        {

            $purchaseRequestApproval->delete();

            return $this->responseSuccess($purchaseRequestApproval, 'Delete Record', 204);
        }

        return $this->responseError([], 'Not found');
    }
}
