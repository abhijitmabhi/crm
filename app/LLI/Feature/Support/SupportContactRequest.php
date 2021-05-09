<?php

namespace LocalheroPortal\LLI\Feature\Support;

use Illuminate\Foundation\Http\FormRequest;

class SupportContactRequest extends FormRequest
{
    public function rules()
    {
        return [
            'subject' => 'required|string',
            'message' => 'required|string'
        ];
    }
}
