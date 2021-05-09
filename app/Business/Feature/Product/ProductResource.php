<?php

namespace LocalheroPortal\Business\Feature\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'min_price' => $this->min_price,
            'payment_options' => $this->paymentOptions,
        ];
    }
}
