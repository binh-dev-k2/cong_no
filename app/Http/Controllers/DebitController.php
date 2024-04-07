<?php

namespace App\Http\Controllers;
use App\Models\Bank;
use Illuminate\Http\Request;

class DebitController extends Controller
{
    function index() {
       // $list_bank = Bank::all();

        return view('admin.debit.index');
    }
}
