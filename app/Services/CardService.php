<?php

namespace App\Services;

use App\Http\Requests\Card\AddCardRequest;
use App\Models\Card;
use Illuminate\Support\Facades\Log;

class CardService
{
    public function updateNote(array $data)
    {
        return Card::where('id', $data['id'])->update(['note' => $data['note']]);
    }

    public function getBlankCards()
    {
        return Card::where('customer_id', null)
            ->with('bank')
            ->get();
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

    function assignCustomer($cardIds, $customerId)
    {
        try {
            return Card::whereIn('id', $cardIds)->update(['customer_id' => $customerId]);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error("message: {$th->getMessage()}, line: {$th->getLine()}");
            return false;
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
