<?php

namespace LocalheroPortal\Models;

use BenSampo\Enum\Enum;

final class LeadState extends Enum
{

    const OPEN = 1;

    const NOT_REACHED = 2;

    const RECALL = 3;

    const NO_INTEREST = 4;

    const APPOINTMENT = 5;

    const BLACKLIST = 6;

    const CLOSED = 7;

    const INVALID = 8;

    const TOO_MANY_TRIES = 9;

    const APPOINTMENT_NEEDED = 10;

    const COMPETITION_PROTECTION = 11;
}
