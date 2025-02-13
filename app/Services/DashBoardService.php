<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Card;
use App\Models\Debt;
use App\Models\Machine;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashBoardService
{
    public function getChartCustomer(): array
    {
        $query = Card::query()->whereHas('customer');
        $now = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->addDays(7)->endOfDay();

        $month = $now->month;
        $year = $now->year;
        $formality = 'Đ';

        $cards = $query->whereBetween(DB::raw("
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

        $totalHasBeenReminded = 0;
        foreach ($cards as $card) {
            if ($card->cardHistories->where('created_at', '>', Carbon::now()->subMonth()->format('Y-m-') . $card->date_due)->count()) {
                $totalHasBeenReminded++;
            }
        }

        $totalCanBeRemind = $cards->count();
        $totalNotReminding = $totalCanBeRemind - $totalHasBeenReminded;

        return [
            'totalCanBeRemind' => $totalCanBeRemind,
            'totalNotReminding' => $totalNotReminding,
            'totalHasBeenReminded' => $totalHasBeenReminded
        ];
    }

    public function getTotalDebit(): array
    {
        $debtQuery = Debt::query();
        $totalDebts = $debtQuery->count();
        $totalAmount = $debtQuery->sum('total_amount');
        $paidDebts = $debtQuery->where('status', Debt::STATUS_PAID)->count();
        $percentCompleted = $totalDebts > 0 ? round(($paidDebts / $totalDebts) * 100, 2) : 0;
        return [
            'percentCompleted' => $percentCompleted,
            'totalAmount' => $totalAmount,
            'countPaidedDebit' => $paidDebts,
        ];
    }

    function getTotalBusiness()
    {
        $query = Business::query()->count();
        return [
            'totalBusiness' => $query
        ];
    }

    public function getCardExpired($data)
    {
        $pageNumber = ($data['start'] ?? 0) / ($data['length'] ?? 1) + 1;
        $pageLength = $data['length'] ?? 50;
        $skip = ($pageNumber - 1) * $pageLength;

        $query = Card::query()
            ->whereNotNull('year_expired')
            ->whereNotNull('month_expired')
            ->whereYear('year_expired', '<=', Carbon::now()->year)
            ->whereMonth('month_expired', '<=', Carbon::now()->month);

        $recordsFiltered = $recordsTotal = $query->count();
        $result = $query->skip($skip)
            ->with(['customer', 'bank'])
            ->take($pageLength)
            ->get();

        return [
            "draw" => $data['draw'] ?? 1,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            'data' => $result
        ];
    }

    public function getMachineFee()
    {
        $total = 0;
        Machine::query()->with('businesses')
            ->get()
            ->map(function ($machine) use (&$total) {
                $machineFeePercent = $machine->fee_percent;
                $machine->businesses->each(function ($business) use ($machineFeePercent, &$total) {
                    $total += $business->fee - ($machineFeePercent * $business->fee / 100);
                });
            });

        return [
            'total' => $total
        ];
    }
}
