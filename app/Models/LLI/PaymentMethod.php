<?php

namespace LocalheroPortal\Models\LLI;

use BenSampo\Enum\Enum;

final class PaymentMethod extends Enum
{
    const CASH = "Nur Barzahlung";

    const CREDITCARD = "Kreditkarte";

    const DEBIT_CC = 'Debitkarte';

    const NFC = 'Mobile Zahlung per NFC';

    const cheque = 'Scheck';

    const pay_pal = 'PayPal';
}
