<?php

use App\Http\Controllers\{CategoryController, AuthController, OrderController, ProductController, StripeController, UserController};
use Illuminate\Support\Facades\Route;;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('products', ProductController::class);
});

Route::controller(AuthController::class)
    ->prefix('auth')
    ->group(function () {
        Route::middleware('guest:sanctum')
            ->group(function () {
                Route::post('register', 'register')->name('auth.register');
                Route::post('login', 'login')->name('auth.login');
            });
        Route::middleware('auth:sanctum')
            ->group(function () {
                Route::post('logout', 'logout')->name('auth.logout');
            });
    });
Route::controller(StripeController::class)->prefix('payment')->group(
    function () {
        Route::middleware('auth:sanctum')->group(
            function () {
                Route::post('handle', 'handle')->name('payment.handle');
                Route::get('confirm', 'confirm')->name('payment.confirm');
                Route::get('index', 'index')->name('payment.index');
            }
        );
    }
);

   /*This "apiResource" line will create the following routes:
GET /products - index method - Show all products                             done
GET /products/create - create method - Show form to create a new product      --
POST /products - store method - Store a new product                          done
GET /products/{product} - show method - Show a specific product              done
GET /products/{product}/edit - edit method - Show form to edit a product      --
PUT/PATCH /products/{product} - update method - Update a specific product    done
DELETE /products/{product} - destroy method - Delete a specific product      done    */
