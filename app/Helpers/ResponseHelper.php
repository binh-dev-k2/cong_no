<?php

namespace App\Helpers;

trait ResponseHelper
{
    function successJsonResponse($code, $data = []) {

        return response()->json(
            [
                'success' => true,
                'data' => $data,

            ],
            $code
        );
    }
    function failJsonResponse($code, $data = []) {
        return response()->json(
            [
                'success' => false,
                'data' => $data,

            ],
            $code
        );
    }
}
