<?php
namespace App\Services;

use App\Models\Debt;


class DebtService{
    function getAllDebt()
    {
        $list_debt = Debt::with('customer', 'card')->get();
        $formatted_debts = [];
        foreach ($list_debt as $debt) {
            $formatted_debt = [
                'id' => $debt->id,
                'formality' => $debt->formality,
                'name' => $debt->customer->name,
                'card_number' => $debt->card->card_number,
                'maturity_fee' => $debt->maturity_fee,
                'withdrawal_fee' => $debt->withdrawal_fee,
                'total' => $debt->total_money,
                'pay_extra' => $debt->pay_extra,
                'status' => $debt->status == 0 ? "Chưa thanh toán" : "Đã thanh toán" ,
            ];

            $formatted_debts[] = $formatted_debt;
        }

        return $formatted_debts;
    }


    // function checkDebt($id){
    //     $debt = Debt::find($id);
    //     if($debt->status == 0){
    //         return "Chưa thanh toán";
    //     }else{
    //         return "Đã thanh toán";
    //     }
    // }
}
