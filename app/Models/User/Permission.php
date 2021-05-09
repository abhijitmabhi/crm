<?php

namespace LocalheroPortal\Models\User;

use BenSampo\Enum\Enum;

final class Permission extends Enum
{
    const ASSIGN_EXPERTS = 'Experten zuweisen';

    const EDIT_ROLES = 'Rollen bearbeiten';

    const EDIT_USERS = 'Nutzer bearbeiten';

    const MAKE_CALLS = 'Telefonieren';

    const VIEW_APPOINTMENTS = 'Termine ansehen';

    const VIEW_EXPERTS = 'Experten ansehen';

    const SEARCH_LEADS = "Leads durchsuchen";

    const SCRAPE_MY_BUSINESS = "GoogleMyBusiness Accounts einsehen.";
}