<?php

namespace App\Services;

use App\Http\Requests\Card\AddCardRequest;
use App\Http\Requests\Card\EditCardRequest;
use App\Models\Card;
use App\Models\CardHistory;
use App\Models\CardMoney;
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
        try {
            DB::beginTransaction();
            $card = Card::create($request->all());
            if ($card) {
                $this->calculateFee($card->id, $card->total_money);
                DB::commit();
                return [
                    'success' => true,
                    'data' => $card
                ];
            }

            DB::rollBack();
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

            $card = Card::where('id', $data['id'])->first();
            if (!$card) {
                return false;
            }

            $result = $card->update([
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

            if ($result) {
                $this->calculateFee($card->id, $data['total_money']);
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

    public function randomMoney()
    {
        return random_int(34000000, 35000000);
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
        return Card::where('customer_id', $id)->update(['customer_id' => null]);
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
        $businnesses = $query->skip($skip)
            ->with(['customer', 'bank'])
            ->take($pageLength)
            ->get();

        return [
            "draw" => $data['draw'] ?? 1,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            'data' => $businnesses
        ];
    }

    public function businessComplete(int $id)
    {
        return Card::where('id', $id)->update(['type' => Card::TYPE_DEBT]);
    }

    public function businessUpdatePayExtra($data)
    {
        return Card::where('id', $data['id'])->update(['pay_extra' => $data['pay_extra']]);
    }

    public function calculateFee($cardId, $totalMoney)
    {
        CardMoney::whereIn('card_id', [$cardId])->delete();
        $data = [];

        while ($totalMoney > 0) {
            $randomMoney = $this->randomMoney();
            if ($totalMoney >= $randomMoney) {
                $data[] = [
                    'card_id' => $cardId,
                    'money' => $randomMoney
                ];
                $totalMoney -= $randomMoney;
            } else {
                $check = false;
                foreach ($data as &$d) {
                    if ($d['money'] + $totalMoney <= 35000000) {
                        $d['money'] += $totalMoney;
                        $check = true;
                        break;
                    }
                }
                if (!$check) {
                    $data[] = [
                        'card_id' => $cardId,
                        'money' => $totalMoney
                    ];
                }
                $totalMoney = 0;
            }
        }

        return CardMoney::insert($data);
    }

    public function businessUpdateMoneyNote($data)
    {
        return CardMoney::where('id', $data['id'])->update(['note' => $data['note']]);
    }

    public function businessGetMoney($id)
    {
        return CardMoney::where('card_id', $id)->get();
    }
}
