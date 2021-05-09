<?php

namespace LocalheroPortal\Models;

use BenSampo\Enum\Enum;

final class CalendarEventType extends Enum
{
    const CALLCENTER_APPOINTMENT = 'callcenter-appointment';

    const GENERAL = 'general';
}