<?php

use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WingController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::middleware(['auth'])->group(function () {

    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('application', [ApplicationController::class, 'index'])->name('applications.index');
    Route::put('application/update/{id}', [ApplicationController::class, 'update'])->name('applications.update');
    Route::post('logout', [ApplicationController::class, 'logout'])->name('logout');


    Route::get('user', [UserController::class, 'index'])->name('user.index');
    Route::get('user/getdata', [UserController::class, 'getdata'])->name('user.getdata');
    Route::get('user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('user/store', [UserController::class, 'store'])->name('user.store');
    Route::delete('user/distroy/{id}', [UserController::class, 'distroy'])->name('user.distroy');
    Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('user/update/{id}', [UserController::class, 'update'])->name('user.update');



    // routes/web.php (admin group er vitore)
    Route::get('roles', [RoleController::class, 'index'])->name('role.index');
    Route::get('roles/getdata', [RoleController::class, 'getdata'])->name('role.getdata');
    Route::get('roles/create', [RoleController::class, 'create'])->name('role.create');
    Route::post('roles/store', [RoleController::class, 'store'])->name('role.store');
    Route::get('roles/edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
    Route::put('roles/update/{id}', [RoleController::class, 'update'])->name('role.update');
    Route::delete('roles/distroy/{id}', [RoleController::class, 'distroy'])->name('role.distroy');
});

    Route::get('wing/getdata', [WingController::class, 'getdata'])
    ->name('wing.getdata');
    Route::resource('wing', WingController::class);
    
    
});

