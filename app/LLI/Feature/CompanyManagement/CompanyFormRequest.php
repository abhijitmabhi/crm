<?php

namespace LocalheroPortal\LLI\Feature\CompanyManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use LocalheroPortal\Core\Rules\Phone;
use LocalheroPortal\Core\Util\PhoneUtil;

class CompanyFormRequest extends FormRequest
{

    public function validationData()
    {
        $data = $this->all();
        if (Arr::has($data, 'phone')) {
            $data['phone'] = PhoneUtil::formatPhoneNumber($data['phone']);
        }
        return $data;
    }

    public function attributes()
    {
        return [
            'phone' => 'Telefonnummer',
            'url' => 'Website',
            'name' => 'Unternehmensname',
            'email' => 'Email',
            'zip' => 'PLZ'
        ];
    }

    public function messages()
    {
        return [
            'zip.digits_between' => 'Die PLZ muss zwischen 4 und 5 Stellen lang sein.',
            'email.email' => 'Die Email ist nicht im passenden Format!',
        ];
    }

    public function rules()
    {
        return [
            'phone' => ['sometimes', 'nullable', new Phone()],
            'url' => 'sometimes|nullable',
            'name' => 'required|string',
            'email' => ['bail', 'required', 'email:rfc,dns', Rule::unique('companies')->ignore(request('id'))],
            'street' => 'sometimes|nullable',
            'zip' => 'nullable|numeric|digits_between:4,5',
            'city' => 'sometimes|nullable',
        ];
    }
}
