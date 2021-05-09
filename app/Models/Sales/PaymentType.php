<?php


namespace LocalheroPortal\Models\Sales;


use BenSampo\Enum\Enum;

final class PaymentType extends Enum
{
    const ONCE = 'once';
    const MONTHLY = 'monthly';
    const YEARLY = 'yearly';
}