<?php

namespace LocalheroPortal\LLI\Feature\Location;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LocalheroPortal\Core\Rules\Phone;
use LocalheroPortal\Models\LLI\CountryCode;

class LocationRequest extends FormRequest
{

    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'address' => 'Addresse',
            'address_addition' => 'Addresszusatz',
            'postcode' => 'PLZ',
            'state' => 'Bundesland',
            'country' => 'Land',
            'phone' => 'Telefonnummer',
            'mobilephone' => 'Handynummer',
            'fax' => 'Fax',
            'email' => 'Email',
            'website' => 'Webseite',
            'description' => 'Beschreibung',
            'category' => 'Kategorie',
            'openinghours' => 'Öffnungszeiten',
            'rank_queries' => 'Suchbegriffe',
            'mainCategory' => 'Hauptkategorie',
            'additionalCategories' => 'Nebenkategorien'
        ];
    }

    public function rules(): array
    {
        return [
            'name' => 'string|required',
            'address' => 'string|required',
            'address_addition' => 'string|nullable',
            'postcode' => 'numeric|required|digits_between:4,5',
            'state' => 'string|required',
            'country' => ['bail', 'string', 'required', Rule::in(CountryCode::asArray())],
            'phone' => ['bail', 'required', new Phone()],
            'mobilephone' => ['bail', 'nullable', new Phone()],
            'fax' => 'string|nullable',
            'email' => 'nullable|email:rfc,dns',
            'website' => 'string|nullable',
            'description' => 'string|nullable|max:750',
            'category' => 'nullable|string',
            'openinghours' => 'array|nullable',
            'rank_queries' => 'array|max:5|nullable',
            'mainCategory' => 'string|required',
            'additionalCategories' => 'array|nullable'
        ];
    }
}
