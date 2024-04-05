<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\DebtService;

class DebtController extends Controller
{
    public $debt_service;
    public function __construct(DebtService $debt_service) {
        $this->debt_service = $debt_service;
    }
}
