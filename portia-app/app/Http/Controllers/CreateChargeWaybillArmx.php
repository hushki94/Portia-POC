<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateWaybillAndEmail;
use Exception;
use Illuminate\Support\Facades\Http;

class CreateChargeWaybillArmx extends Controller
{

    public function create()
    {
        // Logger('omar');
        // request()->validate([
        //     'payload.order.shipping.shipping_address.line1' => 'required',
        //     'payload.order.shipping.shipping_address.city' => 'required',
        //     'payload.order.shipping.shipping_address.postcode' => 'nullable',
        //     'payload.order.shipping.shipping_address.country' => 'required',
        //     'payload.order.shipping.shipping_address.lng' => 'required|numeric',
        //     'payload.order.shipping.shipping_address.lat' => 'required|numeric',
        //     'payload.order.shipping.shipping_address.email' => 'required|email',
        //     'payload.order.shipping.shipping_address.telephone' => 'required|numeric',

        //     'payload.order.shipping.billing_address.line1' => 'required',
        //     'payload.order.shipping.billing_address.city' => 'required',
        //     'payload.order.shipping.billing_address.postcode' => 'nullable',
        //     'payload.order.shipping.billing_address.country' => 'required',
        //     'payload.order.shipping.billing_address.lng' => 'required|numeric',
        //     'payload.order.shipping.billing_address.lat' => 'required|numeric',
        //     'payload.order.shipping.billing_address.email' => 'required|email',
        //     'payload.order.shipping.billing_address.telephone' => 'required|numeric',

        //     'meta.tenant.id' => 'required|numeric',
        //     'meta.tenant.name' => 'required|string',

        //     'payload.details.goods_description' => 'required',
        //     'payload.details.country_of_origin' => 'required',

        //     'payload.order.payment_method' => 'required',

        //     'payload.order.total.amount' => 'required',

        //     'payload.order.purchases.*.dimensions.weight.value' => 'required|numeric',
        //     'payload.order.purchases.*.dimensions.width.value' => 'required|numeric',
        //     'payload.order.purchases.*.dimensions.height.value' => 'required|numeric',
        //     'payload.order.purchases.*.dimensions.length.value' => 'required|numeric',

        //     'payload.order.purchases.*.dimensions.width.unit' => 'required|string',
        //     'payload.order.purchases.*.dimensions.weight.unit' => 'required|string',

        //     'payload.order.purchases.*.quantity' => 'required|numeric',

        // ]);

        $query = request()->all();
        
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
