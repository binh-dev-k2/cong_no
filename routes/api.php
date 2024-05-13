<?php

use App\Http\Controllers\api\CardController;
use App\Http\Controllers\api\DebtController;
use App\Http\Controllers\BusinessController;
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
    //card
    Route::get('card/find', [CardController::class, 'find'])->name('api.card.find');
    Route::post('card/store', [CardController::class, 'store'])->name('api.card.store');
    Route::get('card/blank-cards', [CardController::class, 'getBlankCards'])->name('api.card.blankCards');
    Route::post('card/update-note', [CardController::class, 'updateNote'])->name('api.card.updateNote');
    Route::post('card/remind', [CardController::class, 'remindCard'])->name('api.card.remindCard');
    Route::post('card/edit', [CardController::class, 'edit'])->name('api.card.edit');

    //customer
    Route::post('customer/store', [CustomerController::class, 'store'])->name('api.customer.store');
    Route::post('customer/update', [CustomerController::class, 'update'])->name('api.customer.update');
    Route::post('customer/datatable', [CustomerController::class, 'datatable'])->name('api.customer_showAll');
    Route::delete('customer/delete/{phone}', [CustomerController::class, 'destroy'])->name('api.customer_delete');
    Route::post('customer/debt/showAll/', [DebtController::class, 'getAllDebtByCustomer'])->name('api.debt_showAll');

    //business
    Route::post('business/datatable', [BusinessController::class, 'datatable'])->name('api.business.datatable');
    Route::post('business/complete', [BusinessController::class, 'complete'])->name('api.business.complete');
    Route::post('business/update-pay-extra', [BusinessController::class, 'updatePayExtra'])->name('api.business.updatePayExtra');


    //debt
    Route::get('debt/showAll/', [DebtController::class, 'getAllDebt'])->name('api.debt_showAll');
});

Route::get('debt/showAll/', [DebtController::class, 'getAllDebt'])->name('api.debt_showAll');
Route::delete('customer/delete/{phone}', [CustomerController::class, 'destroy'])->name('api.customer_delete');
Route::post('customer/debt/showAll/', [DebtController::class, 'getAllDebtByCustomer'])->name('api.debt_showAll');
