<?php

namespace App\Services;

use App\Http\Requests\Card\AddCardRequest;
use App\Models\Card;

class CardService
{
    function FindByNumberUnassigned($card_number)
    {
        $card = Card::where(function ($query) use ($card_number) {
            $query->where('card_number', $card_number)
                ->orWhere('account_number', $card_number);
        })->with('bank')->first();
        if ($card) {
            if ($card->customer_id == null) {

                return [
                    'success' => true,
                    'card' => $card
                ];
            }
            return [
                'success' => false,
                'code' => 2,
            ];
        } else {
            return [
                'success' => false,
                'code' => 1,
            ];
        }
    }

    function save(AddCardRequest $request)
    {
        $result = Card::create($request->all());
        if ($result) {
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

    function assignCustomer($card_number_list, $customer_id)
    {
        for ($i = 0; $i < count($card_number_list); $i++) {
            $card = Card::where('card_number', $card_number_list[$i])->first();
            $card->customer_id = $customer_id;
            $card->save();
        }
    }

    function unassignCustomer($id)
    {
        $cards = Card::where('customer_id', $id)->get();
        foreach ($cards as $card) {
            $card->customer_id = null;
            $card->save();
        }
    }
}
