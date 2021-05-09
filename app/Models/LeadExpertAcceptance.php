<?php

namespace LocalheroPortal\Models;

use BenSampo\Enum\Enum;

final class LeadExpertAcceptance extends Enum
{
    const OPEN = 0;
    const ACCEPTED = 1;
    const REJECTED = 2;
}
