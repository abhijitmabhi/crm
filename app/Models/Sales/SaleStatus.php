<?php

namespace LocalheroPortal\Models\Sales;

use BenSampo\Enum\Enum;

final class SaleStatus extends Enum
{
    const ACCEPTED = 1;

    const CANCELED = 2;

    const DENIED = 3;

    const OPEN = 0;
}
