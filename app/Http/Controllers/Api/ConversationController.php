<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationResource;
use App\Models\Chat;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function all(Request $request)
    {
        $senderId   = $request->sender_user_id;
        $senderType = $request->sender_type;
        // $purchaseRequestId  = $request->purchase_request_id;

        $conversations = Conversation::where(function ($query) use ($senderId, $senderType) {
                $query->where('sender_user_id', $senderId)
                    ->where('sender_type', $senderType);
            })->orWhere(function ($query) use ($senderId, $senderType) {
                $query->where('receiver_user_id', $senderId)
                    ->where('receiver_type', $senderType);
            })->get();

        return ConversationResource::collection($conversations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $senderId   = $request->sender_user_id;
        $senderType = $request->sender_type;
        $receiverId   = $request->receiver_user_id;
        $receiverType = $request->receiver_type;
        $purchaseRequestId  = $request->purchase_request_id;
        $requestQuotationId = $request->request_quotation_id;


        $conversation = Conversation::where('purchase_request_id', $purchaseRequestId)
            ->where('request_quotation_id', $requestQuotationId)
            ->where(function ($query) use ($senderId, $senderType, $receiverId, $receiverType) {
                $query->where('sender_user_id', $senderId)
                    ->where('receiver_user_id', $receiverId)
                    ->where('sender_type', $senderType)
                    ->where('receiver_type', $receiverType);
            })->orWhere(function ($query) use ($senderId, $senderType, $receiverId, $receiverType) {
                $query->where('sender_user_id', $receiverId)
                    ->where('receiver_user_id', $senderId)
                    ->where('sender_type', $receiverType)
                    ->where('receiver_type', $senderType);
            })->first();

        if (!$conversation)
        {

            $conversation = Conversation::create([
                'sender_user_id'        => $senderId,
                'receiver_user_id'      => $receiverId,
                'sender_type'           => $senderType,
                'receiver_type'         => $receiverType,
                'purchase_request_id'   => $purchaseRequestId,
                'request_quotation_id'  => $requestQuotationId,
            ]);
        }

        return new ConversationResource($conversation);
    }


    public function destroy($id)
    {
        // $validated = Validator::make(['id' => $id], PurchaseRequestItemValidation::destroy());

        // if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $conversation = Conversation::find($id);

        if ($conversation)
        {

            $conversation->delete();

            Chat::where('conversation_id', $conversation->id)->delete();

            return $this->responseSuccess($conversation, 'Delete conversation', 204);
        }

        return $this->responseError([], 'Not found');
    }
}
