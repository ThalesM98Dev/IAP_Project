<?php

namespace App\Helper;


use Symfony\Component\HttpFoundation\Response as ResponseHTTP;

class ResponseHelper
{
    public static function success($message, $data)
    {
        $response = array(
            'success' => true,
            'message' => $message,
            'data' => $data
        );
        return response()->json($response, ResponseHTTP::HTTP_OK);
    }

    public static function fail($message)
    {
        $response = array(
            'success' => false,
            'message' => $message,
        );
        return response()->json($response, ResponseHTTP::HTTP_OK);
    }

}
