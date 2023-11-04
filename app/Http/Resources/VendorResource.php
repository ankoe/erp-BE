<?php

namespace App\Http\Resources;

use App\Http\Resources\MaterialCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            // 'material_categories'   => MaterialCategoryResource($this->MaterialCategories),
            'material_categories'   => $this->MaterialCategories,
            'name'                  => $this->name,
            'email'                 => $this->email,
            'email_cc'              => $this->email_cc,
            'mobile'                => $this->mobile,
            'slug'                  => $this->slug,
            'created_at'            => $this->created_at,
        ];
    }
}
