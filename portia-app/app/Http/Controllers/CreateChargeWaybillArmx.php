<?php

namespace App\Http\Controllers;

use App\Http\Requests\AramexRequest;
use App\Jobs\GenerateWaybillAndEmail;
use Exception;
use Illuminate\Support\Facades\Http;

class CreateChargeWaybillArmx extends Controller
{
    public function store(AramexRequest $request)
    {

        $query = $request;
        try {
            GenerateWaybillAndEmail::dispatch($query);
            return [
                "Message" => "your order is being processed , you’ll receive an email with the results.",
                "success" => true,
            ];
        } catch (Exception $e) {
            return 'Please try again';
        }
    }

}
