<?php

namespace App\Services;

use App\Http\Requests\Card\AddCardRequest;
use App\Models\Card;
use Illuminate\Support\Facades\Log;

class CardService
{
    function filterDatatable(array $data)
    {
        $pageNumber = ($data['start'] ?? 0) / ($data['length'] ?? 1) + 1;
        $pageLength = $data['length'] ?? 10;
        $skip = ($pageNumber - 1) * $pageLength;

        $query = Card::query()
            ->whereHas('customer');

        if (isset($data['search'])) {
            $search = $data['search'];
            $query->whereHas('customer', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })->orWhere('account_number', 'like', "%{$search}%");
        }

        // switch ($data['order'][0]['column']) {
        //     case '0':
        //         $orderBy = 'id';
        //         break;

        //     default:
        //         $orderBy = 'id';
        //         break;
        // }

        $query->orderBy('customer_id', 'desc');

        $recordsFiltered = $recordsTotal = $query->count();
        $customers = $query->skip($skip)
            ->with(['customer.cards.bank', 'bank', 'cardHistories.user'])
            ->take($pageLength)
            ->get();

        return [
            "draw" => $data['draw'] ?? 1,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            'data' => $customers
        ];
    }

    public function updateNote(array $data)
    {
        return Card::where('id', $data['id'])->update(['note' => $data['note']]);
    }

    public function getBlankCards($data)
    {
        $query = Card::where('customer_id', null);
        if (isset($data['ids'])) {
            $query->orWhereIn('id', $data['ids']);
        }
        return $query->with('bank')->get();
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
