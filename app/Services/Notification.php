<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification as NotificationModel;
use Carbon\Carbon;

class Notification
{

    protected $users, $title, $icon, $background;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function info($title, $message = null)
    {
        // SubjectInfo::Accept => 'Data telah diterima',
        // SubjectInfo::Reject => 'Data ditolak',
        // SubjectInfo::Inform => 'Informasi terbaru',
        $bulkNotification = array();

        foreach ($this->users as $user) {

            array_push($bulkNotification, [
                'user_id'       => $user->id,
                'title'         => $title,
                'type'          => 'info',
                'message'       => $message,
                'created_at'    => Carbon::now(),
            ]);
        }

        NotificationModel::insert($bulkNotification);

        return true;
    }

    public function action($title, $route, $route_param = [], $message = null)
    {
        // $titles = array(
        //     SubjectAction::Accept => 'Data telah diterima',
        //     SubjectAction::Reject => 'Data ditolak',
        //     SubjectAction::Inform => 'Informasi terbaru',
        //     SubjectAction::Submit => 'Data telah dikirim',
        // );
        $bulkNotification = array();

        foreach ($this->users as $user) {

            array_push($bulkNotification, [
                'user_id'       => $user->id,
                'title'         => $title,
                'type'          => 'action',
                'message'       => $message,
                'route'         => $route,
                'route_param'   => json_encode($route_param),
                'created_at'    => Carbon::now(),
            ]);
        }

        NotificationModel::insert($bulkNotification);

        return true;
    }
}