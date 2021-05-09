<?php


namespace LocalheroPortal\Core\Util;


use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class PhoneUtil
{

    /**
     * @param  string  $phoneNumber
     * @param  string  $region
     * @return string
     * @throws NumberParseException
     */
    public static function formatPhoneNumber(string $phoneNumber, string $region = 'DE')
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $formattedNumber = $phoneUtil->parse($phoneNumber, $region);
        $formattedNumber = $phoneUtil->format($formattedNumber, PhoneNumberFormat::INTERNATIONAL);
        return $formattedNumber;
    }

    public static function isValidPhoneNumber(string $phoneNumber, string $region = 'DE')
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            //TODO: turning letters to numbers. improve that?
            $parsedPhone = $phoneUtil->parse($phoneNumber, $region);
            return $phoneUtil->isValidNumberForRegion($parsedPhone, $region);
        } catch (NumberParseException $e) {
            return false;
        }
    }
}