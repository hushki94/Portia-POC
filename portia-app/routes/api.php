<?php

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

Route::post('/calculate-rate' , [CalculateRateAramex::class , 'store']);

Route::post('/charge-waybill' , [CreateChargeWaybillArmx::class , 'store']);

// return Http::dd()->post('https://ws.dev.aramex.net/ShippingAPI.V2/RateCalculator/Service_1_0.svc/json/CalculateRate');
