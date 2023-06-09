<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseRequestApprovalHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"                    => $this->id,
            "purchase_request_id"   => $this->purchase_request_id,
            "role"                  => new RoleResource($this->role),
            "user"                  => new UserResource($this->user),
            "approved_at"           => $this->approved_at,
            "approve_status"        => $this->approve_status,
            "remarks"               => $this->remarks,
            "created_at"            => $this->created_at,
            "updated_at"            => $this->updated_at
        ];
    }
}
