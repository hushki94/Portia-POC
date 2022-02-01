<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AramexRateRequest extends FormRequest
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
            'payload.checkout.shipping.destination.line1'=>'required',
            'payload.checkout.shipping.destination.city'=>'required',
            'payload.checkout.shipping.destination.postcode'=>'nullable',
            'payload.checkout.shipping.destination.country'=>'required',
            'payload.checkout.shipping.destination.lng'=>'required',
            'payload.checkout.shipping.destination.lat'=>'required',

            'payload.checkout.shipping.source.line1'=>'required',
            'payload.checkout.shipping.source.city'=>'required',
            'payload.checkout.shipping.source.postcode'=>'nullable',
            'payload.checkout.shipping.source.country'=>'required',
            'payload.checkout.shipping.source.lng'=>'required',
            'payload.checkout.shipping.source.lat'=>'required',

            'payload.checkout.purchases.*.dimensions.weight.unit'=>'required',
            'payload.checkout.purchases.*.dimensions.weight.value'=>'required',

            'payload.checkout.purchases.*.quantity'=>'required',
        ];
    }
}
