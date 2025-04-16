<?php

namespace App\Services;

use App\Models\Card;
use App\Models\CardHistory;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CardService
{
    public function filterDatatableCustomer(array $data)
    {
        [$pageLength, $skip] = $this->getPaginationInfo($data);

        $query = Card::query()->whereHas('customer');

        switch ((int) $data['view_type']) {
            case 1:
                $now = Carbon::now()->startOfDay();
                $endDate = Carbon::now()->addDays(7)->endOfDay();
                $month = $now->month;
                $year = $now->year;
                $formality = 'Đ';

                $query->whereBetween(DB::raw("
                        STR_TO_DATE(
                            CONCAT(
                                CASE
                                    WHEN $month = 12 AND date_due < {$now->day} THEN $year + 1
                                    ELSE $year
                                END, '-',
                                CASE
                                    WHEN date_due < {$now->day} THEN
                                        CASE WHEN $month = 12 THEN 1 ELSE $month + 1 END
                                    ELSE $month
                                END, '-',
                                date_due
                            ),
                            '%Y-%m-%d'
                        )
                    "), [$now, $endDate])
                    ->whereNull('date_return')
                    ->whereDoesntHave('debts', function ($query) use ($month, $year, $formality) {
                        $query->whereMonth('created_at', $month)
                            ->whereYear('created_at', $year)
                            ->where('formality', $formality);
                });
                break;

            default:
                break;
        }

        if (isset($data['search'])) {
            $search = $data['search'];

            $query->where(function ($query) use ($search) {
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('account_number', 'like', "%{$search}%")
                            ->orWhere('card_number', 'like', "%{$search}%")
                            ->orWhere('account_name', 'like', "%{$search}%");
            });
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

    private function getPaginationInfo(array $data): array
    {
        $pageLength = $data['length'] ?? 50;
        $pageNumber = ($data['start'] ?? 0) / $pageLength + 1;
        $skip = ($pageNumber - 1) * $pageLength;

        return [$pageLength, $skip];
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
        DB::beginTransaction();
        try {
            $this->unassignCustomer([$customerId]);
            Card::whereIn('id', $cardIds)->update(['customer_id' => $customerId]);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("message: {$th->getMessage()}, line: {$th->getLine()}");
            return false;
        }
    }

    public function unassignCustomer(array $customerIds)
    {
        return Card::whereIn('customer_id', $customerIds)->update(['customer_id' => null]);
    }
}
