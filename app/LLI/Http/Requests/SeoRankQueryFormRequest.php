<?php

namespace LocalheroPortal\LLI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeoRankQueryFormRequest extends FormRequest
{
    public function attributes()
    {
        return [
            'keyword' => 'Keyword',
        ];
    }

    public function rules()
    {
        return [
            'keyword' => 'required|string',
        ];
    }
}
