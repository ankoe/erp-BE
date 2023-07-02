<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "conversation_id"   => $this->conversation_id,
            "user_id"           => $this->user_id,
            "message"           => $this->message,
            "type"              => $this->type,
            "created_at"        => $this->created_at,
            "updated_at"        => $this->updated_at,
            "file"              => $this->file ? url(Storage::url($this->file)) : null
        ];
    }
}
