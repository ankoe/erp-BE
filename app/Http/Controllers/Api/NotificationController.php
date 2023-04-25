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

        $notifications = Notification::filter(new NotificationFilter($request))->where('user_id', $user->id)->paginate($request->input('per_page', 10));

        return NotificationResource::collection($notifications);
    }


    public function all(Request $request)
    {
        $validated = Validator::make($request->all(), NotificationValidation::all());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given parameter was invalid');

        $user = auth()->user();

        // Perlu diseragamkan return responsenya
        $notifications = Notification::filter(new NotificationFilter($request))->where('user_id', $user->id)->get();

        return NotificationResource::collection($notifications);
    }


    public function show(Request $request, $id)
    {

        $request['id'] = $id;

        $validated = Validator::make($request->all(), NotificationValidation::show());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $notification = Notification::where(['id' => $id, 'user_id' => $user->id])->first();

        if ($notification)
        {
            return $this->responseSuccess(new NotificationResource($notification), 'Get detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function store(Request $request)
    {

        $validated = Validator::make($request->all(), NotificationValidation::store());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $notification = Notification::Create([
            'user_id'         => $user->id,
            'title'           => $request->title,
            'content'           => $request->content,
            'is_read'           => false,
        ]);

        return $this->responseSuccess($notification, 'Add new account');
    }


    public function update(Request $request, $id)
    {
        $request['id'] = $id;

        $validated = Validator::make($request->all(), NotificationValidation::update());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $notification = Notification::where(['id' => $id, 'user_id' => $user->id])->first();

        if ($notification)
        {
            $notification->title    = $request->title;
            $notification->content    = $request->content;
            $notification->is_read    = $request->is_read;

            $notification->save();

            return $this->responseSuccess(new NotificationResource($notification), 'Update detail');
        }

        return $this->responseError([], 'Not found');
    }


    public function destroy($id)
    {

        $validated = Validator::make(['id' => $id], NotificationValidation::destroy());

        if ($validated->fails()) return $this->responseError($validated->errors(), 'The given data was invalid');

        $user = auth()->user();

        $notification = Notification::where(['id' => $id, 'user_id' => $user->id])->first();

        if ($notification)
        {
            // income dijadikan null atau di ubah ke kategori lainnya

            $notification->delete();

            return $this->responseSuccess($notification, 'Delete Record', 204);
        }

        return $this->responseError([], 'Not found');
    }
}
