<?php

use App\Http\Controllers\BusinessAccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(ClientController::class)
    ->prefix("client")
    ->group(function () {
        Route::post("create-account", "store")->name("client-store");

    });

Route::controller(BusinessAccountController::class)
    ->prefix("business-acount")
    ->group(function () {
        Route::post("create-account", "store")->name("businessAccount-store");
    });



// read the actual_value, what cart have, and connect no external payload to pay.
//actual_value
Route::controller(OrderController::class)
    ->group(function () {
        Route::post("create-order", "store")->name("order-store");

    });

Route::controller(MerchandiseController::class)
    ->group(function () {
        Route::post("create-merchandise", "store")->name("merchandise-store");
    });


// Json: items, is_open, actual_value
Route::controller(CartController::class)
    ->group(function () {
        Route::post("add-item-to-cart", "store")->name("cart-store");
    });