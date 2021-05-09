<?php


namespace LocalheroPortal\Core\Facades\PhoneFormatter;


use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class Formatter
{
    protected $phoneUtil;

    public function __construct()
    {
        $this->phoneUtil = PhoneNumberUtil::getInstance();
    }

    public function formatInternational(string $number) {
        return $this->phoneUtil->format($this->parse($number), PhoneNumberFormat::INTERNATIONAL);
    }

    protected  function parse($number) {
        try {
            return $this->phoneUtil->parse($number, 'DE');
        }
        catch (\Exception $e){
            abort(500, $e->getMessage());
        }
    }
}