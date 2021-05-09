<?php

namespace LocalheroPortal\Models\LLI;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LogEvent extends Enum
{
    const NEW_INVOICE = 'Rechnung';
    const NEW_EVALUATION = 'Auswertung';
}
