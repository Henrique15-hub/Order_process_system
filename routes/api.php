<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserControllerAdmin;
use App\Models\Cart;
use App\Models\Merchandise;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)
    ->prefix('user')
    ->group(function () {
        Route::post('create-account', 'store')->name('user-store');

        Route::delete('delete-account', 'delete')
            ->middleware('auth:sanctum')
            ->name('user-destroy');
    });


Route::controller(OrderController::class)
    ->prefix("order")
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post("create", "store")
            ->can('store', Order::class)
            ->name("order-store");

    });

Route::controller(MerchandiseController::class)
    ->prefix("merchandise")
    ->middleware('auth:sanctum')
    ->group(function () {

        Route::post("create", "store")
            ->can('create', Merchandise::class)
            ->name("merchandise-store");

        Route::post("show", "show")
            ->name("merchandise-show");

        Route::post("update", "update")
            ->can("update", Merchandise::class)
            ->name("merchandise-update");

        Route::delete("delete", "delete")
            ->can("delete", Merchandise::class)
            ->name("merchandise-delete");
    });

Route::controller(CartController::class)
    ->prefix("cart")
    ->middleware('auth:sanctum')
    ->group(function () {

        Route::get("show-items", "showItems")
            ->can("showItems", Cart::class)
            ->name("cart-showItems");

        Route::post("add-merchandise", "addMerchandiseToCart")
            ->can('addMerchandiseToCart', Cart::class)
            ->name("cart-addMerchandiseToCart");

        Route::post("remove-merchandise", "removeMerchandiseToCart")
            ->can("removeMerchandiseToCart", Cart::class)
            ->name("cart-removeMerchandiseToCart");
    });

Route::controller(AuthController::class)
    ->group(function () {
        Route::post('login', 'login')->name('login');
    });

Route::controller(StripeWebhookController::class)
    ->prefix('stripe')
    ->group(function () {
        Route::post('webhook', 'handle');
    });

Route::controller(CheckoutController::class)
    ->prefix('checkout')
    ->group(function () {
        Route::get('aee408847d35e44e99430f0979c3357b85fe8dbb4535a494301198adbee85f27', 'success');
        Route::get('2374d91794b79f4fb4ec9587d2cf00aecc4f9953fab3ed1dd12ab147a0b721f9', 'cancel');
    });

Route::controller(UserControllerAdmin::class)
    ->prefix('admin')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('user-index', 'index')
            ->can('index', User::class)
            ->name('admin-user-index');

        Route::post('user-show', 'show')
            ->can('show', User::class)
            ->name('admin-user-show');

        Route::post('user-update', 'update')
            ->can('update', User::class)
            ->name('admin-user-update');

        Route::delete('user-delete', 'delete')
            ->can('delete', User::class)
            ->name('admin-user-destroy');
    });