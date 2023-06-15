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
            'id'                => $this->id,
            'material_category' => $this->material_category_id ? new MaterialCategoryResource($this->MaterialCategory) : null,
            'name'              => $this->name,
            'email'             => $this->email,
            'slug'              => $this->slug,
            'created_at'        => $this->created_at,
        ];
    }
}
