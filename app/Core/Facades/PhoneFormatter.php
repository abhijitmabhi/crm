<?php


namespace LocalheroPortal\Core\Facades;

use Illuminate\Support\Facades\Facade;
use LocalheroPortal\Core\Facades\PhoneFormatter\Formatter;

class PhoneFormatter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Formatter::class;
    }
}