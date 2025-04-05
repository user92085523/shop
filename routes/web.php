<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Employee;
use Illuminate\Support\Facades\Route;

Route::get('/root', App\Http\Controllers\Root::class)->name('root');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/home', Admin\Home::class)->name('home');
    Route::resource('user', Admin\UserController::class);
});

Route::prefix('employee')->name('employee.')->group(function () {
    Route::get('/home', Employee\Home::class)->name('home');
});

require __DIR__ . '/auth.php';