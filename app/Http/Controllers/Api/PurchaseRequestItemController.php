<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\PurchaseRequestItemFilter;
use App\Http\Resources\PurchaseRequestItemResource;
use App\Http\Validations\PurchaseRequestItemValidation;
use App\Models\PurchaseRequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseRequestItemController extends Controller
{
    public function index(Request $request)
    {
        $validated = Validator::make($request->all(), PurchaseRequestItemValidation::index());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        $purchaseRequestItems = PurchaseRequestItem::filter(new PurchaseRequestItemFilter($request))
                // ->where('user_id', $user->id)
                ->paginate($request->input('per_page', 10));

        return PurchaseRequestItemResource::collection($purchaseRequestItems);
    }


    public function all(Request $request)
    {
        $validated = Validator::make($request->all(), PurchaseRequestItemValidation::all());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        // Perlu diseragamkan return responsenya
        $purchaseRequestItems = PurchaseRequestItem::filter(new PurchaseRequestItemFilter($request))
                // ->where('user_id', $user->id)
                ->get();

        return PurchaseRequestItemResource::collection($purchaseRequestItems);
    }


    public function show(Request $request, $id)
    {

        $request['id'] = $id;

        $validated = Validator::make($request->all(), PurchaseRequestItemValidation::show());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequestItem = PurchaseRequestItem::where('id', $id)
                // ->where('user_id', $user->id)
                ->first();

        if ($purchaseRequestItem)
        {
            return $this->responseSuccess(new PurchaseRequestItemResource($purchaseRequestItem), 'Get detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function store(Request $request)
    {

        $validated = Validator::make($request->all(), PurchaseRequestItemValidation::store());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequestItem = PurchaseRequestItem::create([
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

        return $this->responseSuccess($purchaseRequestItem, 'Add new account');
    }


    public function update(Request $request, $id)
    {
        $request['id'] = $id;

        $validated = Validator::make($request->all(), PurchaseRequestItemValidation::update());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequestItem = PurchaseRequestItem::where('id', $id)
                // ->where('user_id', $user->id)
                ->first();

        if ($purchaseRequestItem)
        {
            $purchaseRequestItem->material_id     = $request->material_id;
            $purchaseRequestItem->price     = $request->price;
            $purchaseRequestItem->description     = $request->description;
            $purchaseRequestItem->quantity     = $request->quantity;
            $purchaseRequestItem->total     = $request->total;
            $purchaseRequestItem->vendor_id     = $request->vendor_id;
            $purchaseRequestItem->branch_id     = $request->branch_id;
            $purchaseRequestItem->expected_at     = $request->expected_at;
            $purchaseRequestItem->file     = $request->file;

            $purchaseRequestItem->save();

            return $this->responseSuccess(new PurchaseRequestItemResource($purchaseRequestItem), 'Update detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function destroy($id)
    {

        $validated = Validator::make(['id' => $id], PurchaseRequestItemValidation::destroy());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $purchaseRequestItem = PurchaseRequestItem::where('id', $id)
                // ->where('user_id', $user->id)
                ->first();

        if ($purchaseRequestItem)
        {

            $purchaseRequestItem->delete();

            return $this->responseSuccess($purchaseRequestItem, 'Delete Record', 204);
        }

        return $this->responseError([], 'Not found');
    }
}
