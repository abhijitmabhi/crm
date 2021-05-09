<?php

namespace LocalheroPortal\Business\Feature\Sale;

use Illuminate\Foundation\Http\FormRequest;

class SaleFormRequest extends FormRequest
{
    public function attributes()
    {
        return [
            'customer_id'       => 'Kunde',
            'expert_id'         => 'Verkäufer', // expert_id can reference any user
            'lead_id'           => 'Lead',
            'product_id'        => 'Produkt',
            'payment_option_id' => 'Zahlungsoption',
            'price'             => 'Preis',
            'contract'          => 'Vertrag',
        ];
    }

    public function rules()
    {
        return [
            'customer_id'       => 'exists:users,id',
            'lead_id'           => 'required_without:customer_id|exists:leads,id',
            'product_id'        => 'required|exists:products,id',
            'payment_option_id' => 'required|exists:payment_options,id',
            'price'             => 'required|numeric',
            'contract'          => 'required|file',
        ];
    }
}
