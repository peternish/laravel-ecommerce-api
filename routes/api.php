<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'admin',
    'middleware' => 'auth:sanctum',
    'namespace' => 'Admin',
    'as' => 'admin.'
], function () {
    Route::apiResource('/users', 'UsersController');
    Route::apiResource('/products', 'ProductsController');
    Route::apiResource('/orders', 'OrdersController');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::prefix('public')->group(function () {
    Route::apiResource('/products', 'ProductsController')->only(['index', 'show']);
    Route::post('/orders', 'OrdersController@store');
});
