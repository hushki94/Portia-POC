<?php

namespace App\Http\Controllers;
use App\Http\Requests\AramexRateRequest;
use App\Services\AramexRateDetails;
use Exception;
use Illuminate\Support\Facades\Http;

class AramexRate extends Controller
{



    public function store(AramexRateRequest $request )
    {

        try {
            $respone = Http::post('https://ws.dev.aramex.net/ShippingAPI.V2/RateCalculator/Service_1_0.svc/json/CalculateRate', AramexRateDetails::getRateDetails($request->all()));


            return [
                "Message" => $respone->json('TotalAmount'),
                "success" => true,
            ];

        } catch (Exception $e) {

            return [
                "Message" => "Something went wrong, please try again.",
                "success" => false,
            ];
        }
    }
}

