<?php

namespace App\Http\Resources;

use App\Http\Resources\PurchaseRequestItemResource;
use App\Http\Resources\PurchaseRequestStatusResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestQuotationResource extends JsonResource
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
            'company_id' => $this->company_id,
            'purchase_request_item_id' => $this->purchase_request_item_id,
            'vendor' => new VendorResource($this->vendor),
            'vendor_price' => $this->vendor_price,
            'vendor_stock' => $this->vendor_stock,
            'is_selected' => $this->is_selected,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
