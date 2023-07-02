<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function all(Request $request)
    {
        // $validated = Validator::make($request->all(), RoleValidation::index());

        // if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $chats = Chat::where('conversation_id', $request->conversation_id)->orderBy('created_at', 'desc')->get();

        return ChatResource::collection($chats);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $validated = Validator::make($request->all(), RoleValidation::store());

        // if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $type = 'text';
        $file = null;
        $filename = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file')->store('public/chat');
            $filename = $request->file('file')->getClientOriginalName();

            $mimeTypes = [
                'image/jpeg',
                'image/png',
                'image/svg+xml',
                'image/jpg',
            ];

            $type = in_array($request->file('file')->getClientMimeType(), $mimeTypes) ? 'image' : 'docs';
        }

        $chat = Chat::create([
                'conversation_id'   => $request->conversation_id,
                'user_id'           => $request->user_id,
                'message'           => $file ? $filename : $request->message,
                'type'              => $type,
                'file'              => $file
            ]);

        return new ChatResource($chat);
    }
}
