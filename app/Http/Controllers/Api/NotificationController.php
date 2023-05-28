<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\NotificationFilter;
use App\Http\Resources\NotificationResource;
use App\Http\Validations\NotificationValidation;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $validated = Validator::make($request->all(), NotificationValidation::index());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        $notifications = Notification::filter(new NotificationFilter($request))
                            ->where('user_id', $user->id)
                            ->orderBy('created_at', 'DESC')
                            ->paginate($request->input('per_page', 10));

        return NotificationResource::collection($notifications);
    }


    public function all(Request $request)
    {
        $validated = Validator::make($request->all(), NotificationValidation::all());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        // Perlu diseragamkan return responsenya
        $notifications = Notification::filter(new NotificationFilter($request))
                            ->where('user_id', $user->id)
                            ->orderBy('created_at', 'DESC')
                            ->get();

        return NotificationResource::collection($notifications);
    }


    public function count(Request $request)
    {
        $count = Notification::where('user_id', auth()->user()->id)
                            ->where('is_read', false)
                            ->count();

        return $this->responseSuccess($count);
    }

    public function readAll(Request $request)
    {
        $notifications = Notification::where('user_id', auth()->user()->id)
                            ->update(['is_read' => true]);

        return $this->responseSuccess($notifications);
    }

    public function readSingle(Request $request, $id)
    {
        $notifications = Notification::where('user_id', auth()->user()->id)
                            ->where('id', $id)
                            ->update(['is_read' => true]);

        return $this->responseSuccess($notifications);
    }
}
