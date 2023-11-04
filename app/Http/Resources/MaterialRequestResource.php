<?php

namespace App\Http\Resources;

use App\Http\Resources\MaterialCategoryResource;
use App\Http\Resources\UnitResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MaterialRequestResource extends JsonResource
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
            'material_category' => $this->material_category_id ? new MaterialCategoryResource($this->materialCategory) : null,
            'name'              => $this->name,
            'description'       => $this->description,
            'unit'              => $this->unit_id ? new UnitResource($this->unit) : null,
            'price'             => $this->price,
            'stock'             => $this->stock,
            'attachment'        => $this->attachment ? url(Storage::url($this->attachment)) : null,
            'user'              => $this->user_id ? new UserResource($this->user) : null,
            'created_at'        => $this->created_at,
        ];
    }
}
