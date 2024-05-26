<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::namespace('App\Http\Controllers')->middleware(["auth"])->group(function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/customer', 'CustomerController@index')->name('customer');
    Route::get('/business', 'BusinessController@index')->name('business');
    Route::get('/debit', 'DebitController@index')->name('debit');
    Route::get('/user', 'UserController@index')->name('user');
});

include "auth.php";

