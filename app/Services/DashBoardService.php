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

    public function getCardExpired($data)
    {
        $pageNumber = ($data['start'] ?? 0) / ($data['length'] ?? 1) + 1;
        $pageLength = $data['length'] ?? 50;
        $skip = ($pageNumber - 1) * $pageLength;

        $query = Card::query()
            ->whereNotNull('year_expired')
            ->whereNotNull('month_expired')
            ->whereRaw("CONCAT(year_expired, '-', LPAD(month_expired, 2, 0)) <= CURDATE()");

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
}
