<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AramexShipment extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'meta.tenant.owner.email' => 'required|email',
            'meta.tenant.name'=>'required',
            'payload.order.shipping_address.line1' => 'required|string|max:255',
            'payload.order.shipping_address.line2' => 'nullable|string|max:255',
            'payload.order.shipping_address.city' => 'required|string',
            'payload.order.shipping_address.country' => 'required|string',
            'payload.order.shipping_address.lng' => 'required',
            'payload.order.shipping_address.lat' => 'required',
            'payload.order.shipping_address.name' => 'nullable|string',
            'payload.order.shipping_address.telephone' => 'nullable|string',
            'payload.order.shipping_address.email'=>'nullable|email',
            'payload.details.company_name' => 'required|string',
            'payload.order.total.amount' => 'required',
            'payload.order.payment_method' => 'required|string',
            'payload.order.billing_address.line1' => 'required|string',
            'payload.order.billing_address.line2' => 'nullable|string',
            'payload.order.billing_address.city' => 'required|string',
            'payload.order.billing_address.country' => 'required|string',
            'payload.order.billing_address.lng' => 'required',
            'payload.order.billing_address.lat' => 'required',
            'payload.order.billing_address.name' => 'nullable|string',
            'payload.order.billing_address.telephone' => 'nullable|string',
            'payload.order.billing_address.email' => 'nullable|email',
            'payload.details.goods_description' => 'required|string',
            'payload.details.country_of_origin' => 'required|string',
            'payload.order.payment_method' =>'required',
            'payload.details.company_name'=> 'required',
            'payload.order.purchases.*.dimensions.weight.value' => 'required',
            'payload.order.purchases.*.dimensions.width.value' => 'required',
            'payload.order.purchases.*.dimensions.height.value' => 'required',
            'payload.order.purchases.*.dimensions.length.value' => 'required',
            'payload.order.purchases.*.dimensions.width.unit' => 'required',
            'payload.order.purchases.*.dimensions.height.unit' => 'required',
            'payload.order.purchases.*.dimensions.length.unit' => 'required',
            'payload.order.purchases.*.dimensions.weight.unit' => 'required',
            'payload.order.purchases.*.quantity' => 'required',
        ];
    }
}
