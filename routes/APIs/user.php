<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {

    Route::middleware('auth:api')->group(function () {
        Route::get('user-profile/{id}', [UserController::class, 'get_user_profile']);
        Route::prefix('posts')->group(function () {
            Route::get('all', [UserController::class, 'get_all_posts_with_their_comments']);
            Route::post('add-comment',[UserController::class, 'add_comment_on_post']);
        });
        Route::prefix('my-posts')->group(function () {
            Route::get('all', [UserController::class, 'get_all_my_posts_with_their_comments']);
            Route::post('add',[UserController::class, 'add_post']);
            Route::delete('delete/{post_id}',[UserController::class, 'delete_my_post']);
            Route::patch('update/{post_id}',[UserController::class, 'update_my_post']);
            Route::post('add-post-images',[UserController::class, 'add_post_images']);
            Route::delete('delete-post-image/{image_id}',[UserController::class, 'delete_post_image']);
        });
    });
});
