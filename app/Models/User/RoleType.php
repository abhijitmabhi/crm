<?php

namespace LocalheroPortal\Models\User;

use BenSampo\Enum\Enum;

final class RoleType extends Enum
{
    const ADMIN = 'admin';

    const CALL_CENTER_AGENT = 'callcenter-agent';

    const CALL_CENTER_SUPERVISOR = 'callcenter-supervisor';

    const CUSTOMER = 'customer';

    const DESIGNER = 'UI/UX Designer';

    const EXPERT = 'Expert';

    const LLI_MANAGER = 'lli-manager';

    const MANAGER = 'manager';

    const FIX_LEADS = 'fix-leads';

    const FINANCE = "finance";

    const CITY = "city";

    const LLI_DATA_SCRAPER = "LLI_DATA_SCRAPER";

    const FOLLOW_UP_AGENT = "FOLLOW_UP_AGENT";
}
