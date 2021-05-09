<?php
namespace LocalheroPortal\Models;

use BenSampo\Enum\Enum;

final class CommentReason extends Enum
{

    const APPOINTMENT = 'APPOINTMENT';

    const APPOINTMENT_NEEDED = "APPOINTMENT_NEEDED";

    const APPOINTMENT_DELETED = "APPOINTMENT_DELETED";

    const RECALL = 'RECALL';

    const OPEN = 'OPEN';

    //TODO: remove usage and migrate DB data
    const REOPEN_OLD = 'Freigabe';

    const REOPEN = 'REOPEN';

    //TODO: remove usage and migrate DB data
    const CORRECTION_OLD = 'Korrektur';

    const CORRECTION = 'CORRECTION';

    const NOT_REACHED = 'NOT_REACHED';

    const NO_INTEREST = 'NO_INTEREST';

    const COMMENT = 'COMMENT';

    const CREATED = 'CREATED';

    const COMPETITION_PROTECTION = 'COMPETITION_PROTECTION';

    const BLACKLIST = 'BLACKLIST';

    const CUSTOMER = 'CUSTOMER';
}
