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
    Route::post('/login', 'login')->middleware(['api.key', 'ip.block']);
    Route::post('/refresh', 'refresh')->middleware(['jwt.verify.apikey', 'ip.block']);
});

Route::controller(SkuController::class)->middleware(['jwt.verify.apikey', 'ip.block'])->group(function () {
    Route::get('sku', 'getSkus');
}); 

Route::get('/getip', function(Request $request) {
    $localIP = getHostByName(php_uname('n'));
    return $localIP;
});