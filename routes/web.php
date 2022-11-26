<?php

use App\User;
use App\Models\Order;
use App\Models\OrderItem;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/commision/report', 'Report\CommissionReportController@index')->name('commission.report');
Route::get('/rank/report', 'Report\RankReportController@index')->name('rank.report');

Route::get('/items/viewData/{id}', 'Report\CommissionReportController@getViewItemsModal');
Route::any('/commision/report/filter', 'Report\CommissionReportController@commissionReportFilter')->name('commission.report.filter');
//Route::get('/commision/report/filter', 'Report\CommissionReportController@commissionReportFilter')->name('commission.report.filter');

Route::get('/test', function () {

    return getDistributor();
});
