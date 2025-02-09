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

function attributes()
{
    return [
        'id' => 'ID',
        'created_at' => 'Ngày tạo',
        'updated_at' => 'Ngày cập nhật',
    ];
}
