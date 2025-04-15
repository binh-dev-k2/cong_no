<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'shortName' => 'required|unique:banks,shortName',
            'code' => 'required|unique:banks,code',
            'logo' => 'required',
        ]);

        $bank = Bank::create($request->all());

        return jsonResponse($bank ? 0 : 1);
    }
}
