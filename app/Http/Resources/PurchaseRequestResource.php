<?php

namespace App\Http\Resources;

use App\Http\Resources\PurchaseRequestApprovalResource;
use App\Http\Resources\PurchaseRequestApprovalHistoryResource;
use App\Http\Resources\PurchaseRequestItemResource;
use App\Http\Resources\PurchaseRequestStatusResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'user' => new UserResource($this->user),
            'status' => new PurchaseRequestStatusResource($this->PurchaseRequestStatus),
            'total' => $this->purchaseRequestItem()->sum('total'),
            'items' => PurchaseRequestItemResource::collection($this->purchaseRequestItem),
            'approvals' => PurchaseRequestApprovalResource::collection($this->purchaseRequestApproval),
            'approval_histories' => PurchaseRequestApprovalHistoryResource::collection($this->purchaseRequestApprovalHistory),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
