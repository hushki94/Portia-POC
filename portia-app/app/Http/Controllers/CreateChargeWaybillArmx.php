<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateWaybillAndEmail;
use Exception;
use Illuminate\Support\Facades\Http;

class CreateChargeWaybillArmx extends Controller
{

    public function create()
    {
        request()->validate([
            'meta.tenant.owner.email' => 'required|email',
            'payload.order.shipping_address.line1' => 'required|string|max:255',
            'payload.order.shipping_address.line2' => 'nullable|string|max:255',
            'payload.order.shipping_address.city' => 'required|string',
            'payload.order.shipping_address.country' => 'required|string',
            'payload.order.shipments.0.source.lng' => 'nullable|string',
            'payload.order.shipments.0.source.lat' => 'nullable|string',
            'payload.order.shipping_address.name' => 'nullable|string',
            'payload.order.shipping_address.telephone' => 'nullable|string',
            'payload.details.company_name' => 'required|string',
            'payload.order.total.amount' => 'required|numeric',
            'payload.order.payment_method' => 'required|string',
            'payload.order.shipments.0.destination.line1' => 'required|string',
            'payload.order.shipments.0.destination.line2' => 'nullable|string',
            'payload.order.shipments.0.destination.city' => 'required|string',
            'payload.order.shipments.0.destination.country' => 'required|string',
            'payload.order.shipments.0.destination.lng' => 'nullable|string',
            'payload.order.shipments.0.destination.lat' => 'nullable|string',
            'payload.order.shipments.0.destination.name' => 'nullable|string',
            'payload.order.shipments.0.destination.telephone' => 'nullable|string',
            'payload.order.shipments.0.destination.email' => 'nullable|string',
            'payload.details.goods_description' => 'required|string',
            'payload.details.country_of_origin' => 'required|string',
            'payload.order.purchases.*.dimensions.weight.value' => 'required|numeric',
            'payload.order.purchases.0.dimensions.weight.unit' => 'required|string',
            'payload.order.purchases.*.quantity' => 'required|numeric',

        ]);
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
