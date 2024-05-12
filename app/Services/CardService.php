<?php

namespace App\Services;

use App\Http\Requests\Card\AddCardRequest;
use App\Http\Requests\Card\EditCardRequest;
use App\Models\Card;
use App\Models\CardHistory;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
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

    public function remindCard($data)
    {
        return CardHistory::create([
            'card_id' => $data['id'],
            'user_id' => auth()->user()->id
        ]);
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

    function update($data)
    {
        try {
            DB::beginTransaction();

            $card = Card::where('id', $data['id'])->first();
            if (!$card) {
                return false;
            }
            $card->update([
                'account_name' => $data['account_name'],
                'account_number' => $data['account_number'],
                'bank_code' => $data['bank_code'],
                'card_number' => $data['card_number'],
                'date_due' => $data['date_due'],
                'date_return' => $data['date_return'],
                'fee_percent' => $data['fee_percent'],
                'formality' => $data['formality'],
                'login_info' => $data['login_info'],
                'note' => $data['note'],
                'pay_extra' => $data['pay_extra'],
                'total_money' => $data['total_money'],
            ]);

            if ($card) {
                DB::commit();
                return true;
            }
            DB::rollBack();
            return false;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("message: {$th->getMessage()}, line: {$th->getLine()}");
            return false;
        }
    }

    function assignCustomer($cardIds, $customerId)
    {
        try {
            DB::beginTransaction();
            $this->unassignCustomer($customerId);
            $result = Card::whereIn('id', $cardIds)->update(['customer_id' => $customerId]);

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
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
