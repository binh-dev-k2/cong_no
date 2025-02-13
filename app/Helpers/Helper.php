<?php


function jsonResponse($code, $data = [], $status = 200)
{
    return response()->json(
        [
            'data' => $data,
            'code' => $code,
        ],
        $status
    );
}
