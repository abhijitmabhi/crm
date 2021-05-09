<?php

namespace LocalheroPortal\Core\Rules;

use Illuminate\Contracts\Validation\Rule;
use LocalheroPortal\Core\Util\PhoneUtil;
use LocalheroPortal\Models\Lead;

class UniqueLeadPhone implements Rule
{

    public function passes($attribute, $value)
    {
        $formatted = PhoneUtil::formatPhoneNumber($value);
        return Lead::wherePhone1($formatted)->orWhere('phone2', '=', $formatted)->doesntExist();
    }

    public function message()
    {
        return __('validation.custom.phone.unique_lead_phone');
    }
}
