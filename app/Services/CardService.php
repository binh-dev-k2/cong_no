<?php

namespace App\Services;

use App\Http\Requests\Card\AddCardRequest;
use App\Models\Card;
use App\Models\CardHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CardService
{
    function filterDatatableCustomer(array $data)
    {
        $pageNumber = ($data['start'] ?? 0) / ($data['length'] ?? 1) + 1;
        $pageLength = $data['length'] ?? 50;
        $skip = ($pageNumber - 1) * $pageLength;

        $query = Card::query()->whereHas('customer');

        if (isset($data['search'])) {
            $search = $data['search'];
            $query->whereHas('customer', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })->orWhere('account_number', 'like', "%{$search}%");
        }

        switch ($data['view_type']) {
            case '1':
                $startDate = Carbon::now()->format('d');
                $endDate = Carbon::now()->addDays(7)->format('d');
                $query->whereBetween('date_due', [$startDate, $endDate]);
                break;

            default:
                break;
        }

        $recordsFiltered = $recordsTotal = $query->count();
        $customers = $query->skip($skip)
            ->with(['customer.cards.bank', 'bank', 'cardHistories.user'])
            ->take($pageLength)
            ->orderBy('customer_id', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return [
            "draw" => $data['draw'] ?? 1,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            'data' => $customers
        ];
    }

    public function find($search)
    {
        return Card::where('card_number', 'like', '%' . $search . '%')->with(['customer', 'bank'])->get();
    }

    public function updateNote(array $data)
    {
        return Card::where('id', $data['id'])->update(['note' => $data['note']]);
    }

    public function getBlankCards()
    {
        return Card::where('customer_id', null)->with('bank')->get();
    }

    public function remindCard($data)
    {
        return CardHistory::create([
            'card_id' => $data['id'],
            'user_id' => auth()->user()->id,
            'customer_id' => $data['customer_id']
        ]);
    }

    function save($data)
    {
        try {
            DB::beginTransaction();
            $card = Card::create($data);
            if ($card) {
                DB::commit();
                return [
                    'success' => true,
                    'data' => []
                ];
            }

            return [
                'success' => false,
                'code' => 1
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("message: {$th->getMessage()}, line: {$th->getLine()}");
            return [
                'success' => false,
                'code' => 1
            ];
        }
    }

    function update($data)
    {
        try {
            DB::beginTransaction();

            $card = Card::findOrFail($data['id']);
            $result = $card->update($data);

            if (!$result) {
                return false;
            }
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("message: {$th->getMessage()}, line: {$th->getLine()}");
            return false;
        }
    }

    function delete($data)
    {
        try {
            DB::beginTransaction();
            $card = Card::where('id', $data['id'])->firstOrFail();

            if ($card->customer->cards()->count() == 1) {
                $card->customer()->delete();
            }

            $card->delete();

            DB::commit();
            return true;
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
            if ($result) {
                DB::commit();
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("message: {$th->getMessage()}, line: {$th->getLine()}");
            return false;
        }
    }

    function unassignCustomer($id)
    {
        try {
            DB::beginTransaction();
            $result = Card::where('customer_id', $id)->update(['customer_id' => null]);
            if ($result) {
                DB::commit();
                return true;
            }

            return false;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("message: {$th->getMessage()}, line: {$th->getLine()}");
            return false;
        }
    }
}
