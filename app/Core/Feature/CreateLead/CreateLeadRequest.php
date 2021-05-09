<?php

namespace LocalheroPortal\Core\Feature\CreateLead;

use Illuminate\Foundation\Http\FormRequest;
use LocalheroPortal\Core\Rules\Phone;
use LocalheroPortal\Core\Rules\UniqueLeadPhone;

class CreateLeadRequest extends FormRequest
{
    public function attributes()
    {
        return [
            'zip' => 'PLZ'
        ];
    }

    public function messages()
    {
        return [
            'company_name.required' => 'Bitte einen Namen für den Lead eingeben.',
            'contact_name.required' => 'Bitte einen Ansprechpartner eingeben.',
            'category.required' => 'Bitte eine Kategorie eingeben.',
            'zip.digits_between' => 'Die PLZ muss zwischen 4 und 5 Stellen lang sein.',
            'email.email' => 'Die Email ist nicht im passenden Format!',
            'website.url' => 'Die URL ist ist nicht im passenden Format!',
        ];
    }

    public function rules()
    {
        return [
            'company_name'       => 'required|string',
            'street'             => 'nullable|string',
            'city'               => 'nullable|string',
            'zip'                => 'nullable|numeric|digits_between:4,5',
            'title'              => 'nullable|string',
            'contact_name'       => 'required|string',
            'additional_contact' => 'nullable|string',
            'phone1'             => ['bail', 'required', new Phone(), new UniqueLeadPhone()],
            'phone2'             => ['bail', 'nullable', new Phone(), new UniqueLeadPhone()],
            'email'              => 'nullable|email:rfc,dns',
            'website'            => 'nullable',
            'category'           => 'required|string',
       //     'agent'              => ['nullable', 'numeric', $isAgent],
        ];
    }
}