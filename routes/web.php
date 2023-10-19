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

Route::get('/', function () {return inertia('Auth/Login'); });
Route::get('/about',  [App\Http\Controllers\HomeController::class, 'about']);
Route::get('/sms',  [App\Http\Controllers\HomeController::class, 'sms']);
Route::get('/service/lists',  [App\Http\Controllers\HomeController::class, 'service']);
Route::get('search', [App\Http\Controllers\JobController::class, 'search']);
Route::middleware(['auth'])->group(function () {
    Route::resource('/home', App\Http\Controllers\HomeController::class);
    Route::resource('/staffs', App\Http\Controllers\StaffController::class);
    Route::resource('/bookings', App\Http\Controllers\BookingController::class);
    Route::resource('/buildings', App\Http\Controllers\BuildingController::class);
    Route::resource('/rooms', App\Http\Controllers\RoomController::class);
    Route::resource('/customers', App\Http\Controllers\CustomerController::class);
    Route::resource('/lists', App\Http\Controllers\ListController::class);
});

require __DIR__.'/auth.php';
