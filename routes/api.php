<?php

use App\Http\Controllers\api\CardController;
use App\Http\Controllers\api\DebtController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebitController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->group(function () {
    //dashboard
    Route::get('dashboard/chart-customer', [DashboardController::class, 'getChartCustomer'])->name('api.dashboard.getChartCustomer');
    Route::get('dashboard/total-debit', [DashboardController::class, 'getTotalDebit'])->name('api.dashboard.getTotalDebit');
    Route::get('dashboard/getTotalBusiness', [DashboardController::class, 'getTotalBusiness'])->name('api.dashboard.getTotalBusiness');

    //card
    Route::post('card/find', [CardController::class, 'find'])->name('api.card.find');
    Route::post('card/store', [CardController::class, 'store'])->name('api.card.store');
    Route::get('card/blank-cards', [CardController::class, 'getBlankCards'])->name('api.card.blankCards');
    Route::post('card/update-note', [CardController::class, 'updateNote'])->name('api.card.updateNote');
    Route::post('card/remind', [CardController::class, 'remindCard'])->name('api.card.remindCard');
    Route::post('card/edit', [CardController::class, 'edit'])->name('api.card.edit');
    Route::delete('card/delete', [CardController::class, 'destroy'])->name('api.card.delete');

    //customer
    Route::post('customer/store', [CustomerController::class, 'store'])->name('api.customer.store');
    Route::post('customer/update', [CustomerController::class, 'update'])->name('api.customer.update');
    Route::post('customer/datatable', [CustomerController::class, 'datatable'])->name('api.customer_showAll');
    Route::delete('customer/delete/', [CustomerController::class, 'destroy'])->name('api.customer_delete');

    //business
    Route::post('business/datatable', [BusinessController::class, 'datatable'])->name('api.business.datatable');
    Route::post('business/complete', [BusinessController::class, 'complete'])->name('api.business.complete');
    Route::post('business/update-pay-extra', [BusinessController::class, 'updatePayExtra'])->name('api.business.updatePayExtra');
    Route::post('business/view-money', [BusinessController::class, 'viewMoney'])->name('api.business.viewMoney');
    Route::post('business/update-business-money', [BusinessController::class, 'updateBusinessMoney'])->name('api.business.updateBusinessMoney');
    Route::post('business/store', [BusinessController::class, 'store'])->name('api.business.store');
    Route::post('business/update', [BusinessController::class, 'update'])->name('api.business.update');
    Route::post('business/delete', [BusinessController::class, 'delete'])->name('api.business.delete');
    Route::get('business/edit-setting', [BusinessController::class, 'editSetting'])->name('api.business.editSetting');
    Route::post('business/update-setting', [BusinessController::class, 'updateSetting'])->name('api.business.updateSetting');

    //debt
    Route::post('debit/showAll/', [DebitController::class, 'showAllDebits'])->name('api.debit_showAll');
    Route::post('debit/updateStatus/', [DebitController::class, 'update'])->name('api.debit_updateStatus');

    //user
    Route::post('user/datatable', [UserController::class, 'datatable'])->name('api.user.datatable');
    Route::post('user/delete', [UserController::class, 'delete'])->name('api.user.delete');
    Route::post('user/register', [UserController::class, 'register'])->name('api.user.register');
});
