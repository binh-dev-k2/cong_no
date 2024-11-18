<?php

use Illuminate\Support\Facades\Artisan;
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

Route::
        namespace('App\Http\Controllers')->middleware(["auth"])->group(function () {
            Route::get('/', 'DashboardController@index')->name('dashboard');
            Route::get('/customer', 'CustomerController@index')->name('customer');
            Route::get('/business', 'BusinessController@index')->name('business');
            Route::get('/debit', 'DebitController@index')->name('debit');
            Route::get('/user', 'UserController@index')->name('user');
        });

Route::get('/@@binhcoder02/update', function () {
    exec('sh ' . base_path('pull_code.sh'), $output, $return_var);

    if ($return_var !== 0) {
        return response("Failed to update: " . implode("<br>", $output), 500);
    }

    return "Updated successfully: " . implode("<br>", $output);
});

include "auth.php";

// Route::get('setup', function() {
//     Artisan::call('route:cache');
//     Artisan::call('route:clear');
//     Artisan::call('config:cache');
//     Artisan::call('config:clear');
//     Artisan::call('optimize');
// });
