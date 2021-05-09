<?php

namespace LocalheroPortal\Models\LLI;

use BenSampo\Enum\Enum;

final class LocationState extends Enum
{

    const CITATIONS_DONE = "CITATIONS_DONE";

    const ACCESS_DATA_SENT = "ACCESS_DATA_SENT";

    const ACTIVATED = "ACTIVATED";

    const INFORMATION_EXIST = "INFORMATION_EXIST";

    const PICTURES_EXIST = "PICTURES_EXIST";

    const HAS_PROBLEM = "HAS_PROBLEM";

    const STATISTICS_READY = "STATISTICS_READY";

}
