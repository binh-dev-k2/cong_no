<?php

namespace App\Services;

use App\Http\Requests\Card\AddCardRequest;
use App\Models\Card;
use App\Models\CardHistory;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CardService
{
    function filterDatatableCustomer(array $data)
    {
        $pageNumber = ($data['start'] ?? 0) / ($data['length'] ?? 1) + 1;
        $pageLength = $data['length'] ?? 10;
        $skip = ($pageNumber - 1) * $pageLength;

        $query = Card::query()->whereHas('customer');

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

        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addDays(7);

        $query->whereBetween('date_due', [$startDate, $endDate])->orderBy('customer_id', 'desc');

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


    // BUSINESS
    public function filterDatatableBusiness(array $data)
    {
        $pageNumber = ($data['start'] ?? 0) / ($data['length'] ?? 1) + 1;
        $pageLength = $data['length'] ?? 10;
        $skip = ($pageNumber - 1) * $pageLength;

        $query = Card::query()->where('type', Card::TYPE_BUSINESS)->whereHas('customer');

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
            ->with(['customer', 'bank', 'money'])
            ->take($pageLength)
            ->get();

        return [
            "draw" => $data['draw'] ?? 1,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            'data' => $customers
        ];
    }

    public function businessComplete(int $id)
    {
        return Card::where('id', $id)->update(['type' => Card::TYPE_DEBT]);
    }

    public function businessUpdateNote($data)
    {
    }

    public function businessUpdatePayExtra($data)
    {
        return Card::where('id', $data['id'])->update(['pay_extra' => $data['pay_extra']]);
    }
}
