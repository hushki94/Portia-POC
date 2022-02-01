<?php

namespace App\Jobs;

use App\Mail\WaybillMail;
use App\Services\AramexDetails;
use App\Services\AramexPickupDetails;
use App\Services\AramexShipmentDetails;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Log\Logger;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class WaybillGenerator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $query;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * Execute the job.
     *
     * @return void
     */


    public function handle()
    {


        $shipment = Http::post('https://ws.dev.aramex.net/ShippingAPI.V2/Shipping/Service_1_0.svc/json/CreateShipments',AramexShipmentDetails::getAramexShipmentDetails($this->query));


        $pickupLabel = Http::post('https://ws.dev.aramex.net/ShippingAPI.V2/Shipping/Service_1_0.svc/json/CreatePickup',AramexPickupDetails::getAramexPickupDetails($shipment , $this->query));



        Mail::to($this->query['meta']['tenant']['owner']['email'])
            ->queue(new WaybillMail($pickupLabel->json('ProcessedPickup')['ProcessedShipments'][0]['ShipmentLabel']['LabelURL']));

    }
}
