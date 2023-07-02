<?php

namespace App\Http\Resources;

use App\Http\Resources\ChatResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lastChat = ChatResource::collection($this->lastChat);
        // dd($this->lastChat);

        return [
            "id"                => $this->id,
            "sender"            => [
                'id'    => $this->sender->id,
                'name'  => $this->sender->name,
            ],
            "receiver"          => [
                'id'    => $this->receiver->id,
                'name'  => $this->receiver->name,
            ],
            "sender_type"       => $this->sender_type,
            "receiver_type"     => $this->receiver_type,
            "purchase_request"  => [
                'id'        => $this->purchaseRequest->id,
                'code'      => $this->purchaseRequest->code,
                'code_rfq'  => $this->purchaseRequest->code_rfq,
            ],
            "request_quotation" => [
                'id'        => $this->requestQuotation?->id,
            ],
             "chats"        => ChatResource::collection($this->lastChat),
            "created_at"        => $this->created_at,
            "updated_at"        => $this->updated_at,
        ];
    }
}
