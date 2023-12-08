<?php

namespace App\Http\Resources;

use App\Http\Resources\PurchaseRequestItemResource;
use App\Http\Resources\PurchaseRequestStatusResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'id'                        => $this->id,
            'company_id'                => $this->company_id,
            'purchase_request_item_id'  => $this->purchase_request_item_id,
            'vendor'                    => new VendorResource($this->vendor),
            'vendor_price'              => $this->vendor_price,
            'vendor_stock'              => $this->vendor_stock,
            'vendor_incoterms'          => $this->vendor_incoterms,
            'vendor_is_agree'           => $this->vendor_is_agree,
            'is_selected'               => $this->is_selected,

            'vendor_delivery_at'        => $this->vendor_delivery_at,
            'vendor_attachment_header'  => $this->vendor_attachment_header ? url(Storage::url($this->vendor_attachment_header)) : null,
            'vendor_attachment_item'    => $this->vendor_attachment_item ? url(Storage::url($this->vendor_attachment_item)) : null,
            'vendor_remarks'            => $this->vendor_remarks,
            'created_at'                => $this->created_at,
            'updated_at'                => $this->updated_at,
        ];
    }
}
