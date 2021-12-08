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

Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', 'AuthController@logout');
});

Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth:sanctum', 'is_admin'],
    'namespace' => 'Admin',
    'as' => 'admin.'
], function () {
    Route::apiResource('/users', 'UsersController');
    Route::apiResource('/products', 'ProductsController');
    Route::apiResource('/orders', 'OrdersController');
});

Route::prefix('public')->group(function () {
    Route::apiResource('/products', 'ProductsController')->only(['index', 'show']);
    Route::post('/orders', 'OrdersController@store');
});
