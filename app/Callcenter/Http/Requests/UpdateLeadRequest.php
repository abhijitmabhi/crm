<?php

namespace LocalheroPortal\Callcenter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use LocalheroPortal\Core\Rules\Phone;
use LocalheroPortal\Core\Util\PhoneUtil;

class UpdateLeadRequest extends FormRequest
{

    public function validationData()
    {
        $data = $this->all();
        if (Arr::has($data, 'phone1')) {
            $data['phone1'] = PhoneUtil::formatPhoneNumber($data['phone1']);
        }
        return $data;
    }

    public function attributes()
    {
        return [
            'zip' => 'PLZ',
            'phone1' => 'Telefonnummer',
            'status' => 'Status'
        ];
    }

    public function messages()
    {
        return [
            'zip.digits_between' => 'PLZ muss zwischen 4 und 5 Stellen lang sein.',
            'phone1.unique' => 'Lead mit dieser Telefonnummer existiert bereits.'
        ];
    }

    public function rules()
    {
        return [
            'expert_status'       => 'sometimes|numeric',
            'expert_id'           => 'sometimes|numeric',
            'company_name'        => 'sometimes|string',
            'street'              => 'sometimes|nullable|string',
            'city'                => 'sometimes|nullable|string',
            'zip'                 => 'sometimes|nullable|numeric|digits_between:4,5',
            'title'               => 'sometimes|nullable|string',
            'contact_name'        => 'sometimes|nullable|string',
            'additional_contacts' => 'sometimes|nullable|string',
            'phone1'              => ['bail', 'sometimes', new Phone(), Rule::unique('leads')->ignore(request('lead'))],
            'email'               => 'sometimes|nullable|string',
            'website'             => 'sometimes|nullable|string',
            'category'            => 'sometimes|nullable|string',
            'important_note'      => 'sometimes|nullable|string'
        ];
    }
}
