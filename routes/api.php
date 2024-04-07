<?php

use App\Http\Controllers\api\CardController;
use App\Http\Controllers\api\DebtController;
use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('card/find', [CardController::class, 'find'])->name('api.card_find');
    Route::post('card/store', [CardController::class, 'store'])->name('api.card_store');
    Route::post('customer/store', [CustomerController::class, 'store'])->name('api.customer_store');
    Route::get('customer/showAll/', [CustomerController::class, 'showAllCustomer'])->name('api.customer_showAll');
    Route::delete('customer/delete/{phone}', [CustomerController::class, 'destroy'])->name('api.customer_delete');
    Route::get('debt/showAll/', [DebtController::class, 'getAllDebt'])->name('api.debt_showAll');
    Route::post('customer/debt/showAll/', [DebtController::class, 'getAllDebtByCustomer'])->name('api.debt_showAll');


});
Route::get('customer/showAll/', [CustomerController::class, 'showAllCustomer'])->name('api.customer_showAll');
Route::get('debt/showAll/', [DebtController::class, 'getAllDebt'])->name('api.debt_showAll');
Route::delete('customer/delete/{phone}', [CustomerController::class, 'destroy'])->name('api.customer_delete');
