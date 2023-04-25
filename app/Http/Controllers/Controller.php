<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * return success response.
     *
     * @return \Illuminate\Http\Response
     */
    public function responseSuccess($result = [], $message = 'Task done', $code = 200)
    {

        $response = [
            "code" => $code,
            "status" => "SUCCESS",
            'message' => $message,
            'data'    => $result,
        ];

        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function responseError($errorMessages = [], $message = 'Something happen', $code = 200)
    {

        $response = [
            "code" => $code,
            "status" => "FAILED",
            'message' => $message,
        ];

        if (!empty($errorMessages)) $response['data'] = $errorMessages;

        return response()->json($response, $code);
    }
}
