<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SkuController;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->middleware(['ip.block', 'api.key']);
    Route::post('/refresh', 'refresh')->middleware(['ip.block', 'jwt.verify.apikey']);
});

Route::controller(SkuController::class)->middleware(['ip.block', 'jwt.verify.apikey'])->group(function () {
    Route::get('sku', 'getSkus');
}); 

Route::get('/getip', function(Request $request) {
    $localIP = $_SERVER['SERVER_ADDR'];
    return $localIP;
});