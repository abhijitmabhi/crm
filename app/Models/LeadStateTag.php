<?php

namespace LocalheroPortal\Models;

use BenSampo\Enum\Enum;

final class LeadStateTag extends Enum
{

    const FOLLOW_UP_ACCEPTED = "FOLLOW_UP_ACCEPTED";

    const FOLLOW_UP_REJECTED = "FOLLOW_UP_REJECTED";

    const FOLLOW_UP_SKIPPED = "FOLLOW_UP_SKIPPED";

}
