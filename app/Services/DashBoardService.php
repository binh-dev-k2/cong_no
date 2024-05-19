<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Card;
use App\Models\Debt;
use Carbon\Carbon;

class DashBoardService
{
    public function getChartCustomer(): array
    {
        $query = Card::query()->whereHas('customer');
        $startDate = Carbon::now()->format('d');
        $endDate = Carbon::now()->addDays(7)->format('d');
        $cards = $query->whereBetween('date_due', [$startDate, $endDate])->get();

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
}
