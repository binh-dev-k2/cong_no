<?php
namespace App\Services;
use App\Models\Business;
use App\Models\Card;
use App\Models\Customer;
use App\Models\Debt;
use Carbon\Carbon;

class DashBoardService
{
    public function getDounutChartData()
    {
        $query = Card::query()->whereHas('customer');
        $totalData = $query->count();
        $startDate = Carbon::now()->format('d');
        $endDate = Carbon::now()->addDays(7)->format('d');
        $query->whereBetween('date_due', [$startDate, $endDate]);
        $totalDataDue = $query->count();
        $countRemind = $query->whereHas('cardHistories')->count();
        $countnotRemind = $totalDataDue - $countRemind;
        $data = [
            'totalData' => $totalData,
            'totalDataDue' => $totalDataDue,
            'totalRemind' => $countRemind,
            'totalNotRemind' => $countnotRemind
        ];
        return $data;

    }

    function getProcessData(){
        $query = Debt::query();
        $totalData = $query->count();
        $totalFee = $query->sum('fee');
        $totalAmount = $query->sum('total_amount');
        $totalMoney = $totalFee + $totalAmount;
        $isDone = $query->where('status', 1)->count();
        $isNotDone = $totalData - $isDone;
        $percent = ($isDone / $totalData) * 100;
        return [
            'percent' => $percent,
            'totalMoney' => $totalMoney,
            'totalData' => $totalData,
            'isDone' => $isDone,
            'isNotDone' => $isNotDone
        ];

    }

    function getTotalBusiness(){
        $query = Business::query()->count();
        return [
            'totalBusiness' => $query
        ];
    }
}
