<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Employee;
use App\Http\Controllers\Customer;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->controller(Admin\AuthController::class)->group(function () {
    Route::get('login', 'showLogin')->name('showLogin');
    Route::get('authenticate', 'login')->name('auth');
    Route::get('logout', 'logout')->name('logout');
});

Route::prefix('employee')->name('employee.')->controller(Employee\AuthController::class)->group(function () {
    Route::get('login', 'showLogin')->name('showLogin');
    Route::get('authenticate', 'login')->name('auth');
    Route::get('logout', 'logout')->name('logout');
});