<?php

use App\Http\Controllers\AramexRate;
use App\Http\Controllers\AramexWaybill;
use App\Http\Controllers\CalculateRateAramex;
use App\Http\Controllers\CreateChargeWaybillArmx;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/calculate-rate' , [AramexRate::class , 'store']);

Route::post('/charge-waybill' , [AramexWaybill::class , 'store']);

