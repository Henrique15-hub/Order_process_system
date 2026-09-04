<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Models\Merchandise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(UserController::class)
    ->group(function () {
        Route::post('create-account', 'store')->name('user-store');
    });


Route::controller(OrderController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post("create-order", "store")->name("order-store");

    });

Route::controller(MerchandiseController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post("create-merchandise", "store")
            ->can('create', Merchandise::class)
            ->name("merchandise-store");
    });

Route::controller(CartController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post("add-item-to-cart", "store")->name("cart-store");
    });

Route::controller(AuthController::class)
    ->group(function () {
        Route::post('login', 'login')->name('login');
    });
