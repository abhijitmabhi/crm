<?php


namespace LocalheroPortal\Core\Feature\Search;


use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'searchTerm' => 'string|nullable',
            'phone' => 'string|nullable',
            'email' => 'string|nullable|email:rfc,dns',
            'limit' => 'integer|nullable',
        ];
    }
}