<?php

use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebitController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth')->group(function () {
    //dashboard
    Route::get('dashboard/chart-customer', [DashboardController::class, 'getChartCustomer'])->name('api.dashboard.getChartCustomer')->middleware('can:dashboard');
    Route::get('dashboard/total-debit', [DashboardController::class, 'getTotalDebit'])->name('api.dashboard.getTotalDebit')->middleware('can:dashboard');
    Route::get('dashboard/total-business', [DashboardController::class, 'getTotalBusiness'])->name('api.dashboard.getTotalBusiness')->middleware('can:dashboard');
    Route::post('dashboard/card-expired', [DashboardController::class, 'getCardExpired'])->name('api.dashboard.getCardExpired')->middleware('can:dashboard');

    //card
    Route::post('card/store', [CardController::class, 'store'])->name('api.card.store')->middleware('can:customer-create');
    Route::post('card/find', [CardController::class, 'find'])->name('api.card.find')->middleware(['can:customer-create', 'can:customer-update']);
    Route::get('card/blank-cards', [CardController::class, 'getBlankCards'])->name('api.card.blankCards')->middleware(['can:customer-create', 'can:customer-update']);
    Route::post('card/update-note', [CardController::class, 'updateNote'])->name('api.card.updateNote')->middleware('can:customer-update');
    Route::post('card/remind', [CardController::class, 'remindCard'])->name('api.card.remindCard')->middleware('can:customer-update');
    Route::post('card/edit', [CardController::class, 'edit'])->name('api.card.edit')->middleware('can:customer-update');
    Route::delete('card/delete', [CardController::class, 'destroy'])->name('api.card.delete')->middleware('can:customer-delete');

    //customer
    Route::post('customer/store', [CustomerController::class, 'store'])->name('api.customer.store')->middleware('can:customer-create');
    Route::post('customer/update', [CustomerController::class, 'update'])->name('api.customer.update')->middleware('can:customer-update');
    Route::post('customer/datatable', [CustomerController::class, 'datatable'])->name('api.customer_showAll')->middleware('can:customer-view');
    Route::delete('customer/delete/', [CustomerController::class, 'destroy'])->name('api.customer_delete')->middleware('can:customer-delete');

    //business
    Route::post('business/datatable', [BusinessController::class, 'datatable'])->name('api.business.datatable')->middleware('can:business-view');
    Route::post('business/store', [BusinessController::class, 'store'])->name('api.business.store')->middleware('can:business-create');
    Route::post('business/update', [BusinessController::class, 'update'])->name('api.business.update')->middleware('can:business-create');
    Route::post('business/delete', [BusinessController::class, 'delete'])->name('api.business.delete')->middleware('can:business-create');
    Route::post('business/complete', [BusinessController::class, 'complete'])->name('api.business.complete')->middleware('can:business-update');
    Route::post('business/update-pay-extra', [BusinessController::class, 'updatePayExtra'])->name('api.business.updatePayExtra')->middleware('can:business-update');
    Route::post('business/update-business-money', [BusinessController::class, 'updateBusinessMoney'])->name('api.business.updateBusinessMoney')->middleware('can:business-update');
    Route::get('business/edit-setting', [BusinessController::class, 'editSetting'])->name('api.business.editSetting')->middleware('can:business-update');
    Route::post('business/update-setting', [BusinessController::class, 'updateSetting'])->name('api.business.updateSetting')->middleware('can:business-update');
    Route::post('business/update-note', [BusinessController::class, 'updateNote'])->name('api.business.updateNote')->middleware('can:business-update');

    //debt
    Route::post('debit/showAll', [DebitController::class, 'showAllDebits'])->name('api.debit_showAll')->middleware('can:debit-view');
    Route::post('debit/get-total-money', [DebitController::class, 'getTotalMoney'])->name('api.debit.getTotalMoney')->middleware('can:debit-view');
    Route::post('debit/view-money', [DebitController::class, 'viewMoney'])->name('api.debit.viewMoney')->middleware('can:debit-view');
    Route::post('debit/updateStatus', [DebitController::class, 'update'])->name('api.debit_updateStatus')->middleware('can:debit-update');

    //user
    Route::post('user/datatable', [UserController::class, 'datatable'])->name('api.user.datatable')->middleware('can:user-view');
    Route::post('user/update-role', [UserController::class, 'updateRole'])->name('api.user.updateRole')->middleware('can:user-update');
    Route::post('user/delete', [UserController::class, 'delete'])->name('api.user.delete')->middleware('can:user-delete');
    Route::post('user/register', [UserController::class, 'register'])->name('api.user.register')->middleware('can:user-create');

    //machine
    Route::post('machine/datatable', [MachineController::class, 'datatable'])->name('api.machine.list')->middleware('can:machine-view');
    Route::post('machine/store', [MachineController::class, 'store'])->name('api.machine.store')->middleware('can:machine-create');
    Route::post('machine/update', [MachineController::class, 'update'])->name('api.machine.update')->middleware('can:machine-update');
    Route::post('machine/delete', [MachineController::class, 'delete'])->name('api.machine.delete')->middleware('can:machine-delete');

    //role
    Route::post('role/datatable', [RoleController::class, 'datatable'])->name('api.role.list');
    Route::post('role/store', [RoleController::class, 'store'])->name('api.role.store');
    Route::post('role/update', [RoleController::class, 'update'])->name('api.role.update');
    Route::post('role/delete', [RoleController::class, 'delete'])->name('api.role.delete');
});
