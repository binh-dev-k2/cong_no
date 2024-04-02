<?php

namespace App\Services;

use App\Http\Requests\Card\AddCardRequest;
use App\Models\Card;

class CardService
{
    function findByNumberUnassigned($card_number)
    {
        $card = Card::where(function ($query) use ($card_number) {
            $query->where('card_number', $card_number)
                ->orWhere('account_number', $card_number);
        })->with('bank')->first();
        if ($card) {
            if($card->customer_id == null) {

                return [
                    'success' => true,
                    'card' => $card
                ];
            }
            // Thẻ đã được khách hàng khác sử dụng
            return [
                'success' => false,
                'code'=> 2,
            ];
        }else {
            // Không tìm thấy thẻ trong hệ thống
            return [
                'success' => false,
                'code' => 1,
            ];
        }
    }
    function save(AddCardRequest $request) {
        $result = Card::create($request->all());
        if($result) {
            return [
                'success' => true,
                'data' => $result
            ];
        }
        return [
            'success' => false,
            'code' => 1
        ];
    }
}
