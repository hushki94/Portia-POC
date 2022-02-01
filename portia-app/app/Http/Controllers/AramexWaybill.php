<?php

namespace App\Http\Controllers;

use App\Http\Requests\AramexRequest;
use App\Http\Requests\AramexShipment;
use App\Jobs\WaybillGenerator;
use App\Services\AramexDetails;
use Exception;
use Illuminate\Support\Facades\Http;

class AramexWaybill extends Controller
{
    public function store(AramexShipment $request)
    {
        try {
            WaybillGenerator::dispatch($request->all());

            return [
                "Message" => "your order is being processed , you’ll receive an email with the results.",
                "success" => true,
            ];

        } catch (Exception $e) {

            return [
                "Message" => "Something went error, please try again.",
                "success" => false,
            ];
        }
    }

}
