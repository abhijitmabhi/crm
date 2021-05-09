<?php

namespace LocalheroPortal\Core\Rules;

use Illuminate\Contracts\Validation\Rule;
use LocalheroPortal\Core\Util\PhoneUtil;

class Phone implements Rule
{

    public function passes($attribute, $value)
    {
        return PhoneUtil::isValidPhoneNumber($value);
    }

    public function message()
    {
        return __('validation.custom.phone.valid-DE-number');
    }
}
