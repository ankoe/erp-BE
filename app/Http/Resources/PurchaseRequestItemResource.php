<?php

namespace App\Http\Resources;

use App\Http\Resources\BranchResource;
use App\Http\Resources\MaterialResource;
use App\Http\Resources\VendorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PurchaseRequestItemResource extends JsonResource
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
            "material"              => new MaterialResource($this->material),
            "price"                 => $this->price,
            "description"           => $this->description,
            "quantity"              => $this->quantity,
            "total"                 => $this->total,
            "vendor"                => new VendorResource($this->vendor),
            "branch"                => new BranchResource($this->branch),
            "expected_at"           => $this->expected_at,
            "file"                  => url(Storage::url($this->file)),
            "created_at"            => $this->created_at,
            "updated_at"            => $this->updated_at
        ];
    }
}
