<?php

use App\Http\Controllers\CancelOrdersController;
use App\Http\Controllers\OrderBooksController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreOrdersController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (): void {
    Route::get('orders', OrderBooksController::class)->name('api.orders.books');
    Route::post('orders', StoreOrdersController::class)->name('api.orders.store');
    Route::post('orders/{order}/cancel', CancelOrdersController::class)->name('api.orders.cancel');
    Route::get('/profile', ProfileController::class)->name('api.profile');
});
