<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {

    Route::middleware('auth:api')->group(function () {
        Route::prefix('users-management')->group(function () {
            Route::get('all',[AdminController::class, 'get_all_users']);
            Route::get('all-admins',[AdminController::class, 'get_all_admins']);
            Route::get('all-regular-users',[AdminController::class, 'get_all_regular_users']);
            Route::post('add',[AdminController::class, 'add_user']);
            Route::delete('delete/{id}',[AdminController::class, 'delete_user']);
            Route::patch('update-activation/{id}',[AdminController::class, 'update_activation_user']);
        });
    });
});
