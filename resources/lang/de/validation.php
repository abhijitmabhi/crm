<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'        => ':attribute muss akzeptiert werden.',
    'active_url'      => ':attribute ist keine gültige URL.',
    'after'           => ':attribute muss ein Datum nach dem :date sein.',
    'after_or_equal'  => ':attribute muss ein Datum nach oder gleichzeitig wie :date sein.',
    'alpha'           => ':attribute darf nur aus Buchstaben bestehen.',
    'alpha_dash'      => ':attribute darf nur aus Buchstaben, Zahlen, Binde- und Unterstrichen bestehen.',
    'alpha_num'       => ':attribute darf nur aus Buchstaben und Zahlen bestehen.',
    'array'           => ':attribute muss eine Liste sein.',
    'before'          => ':attribute muss ein Datum vor dem :date sein.',
    'before_or_equal' => ':attribute muss ein Datum vor oder gleichzeitig wie :date sein.',
    'between'         => [
        'numeric' => ':attribute muss eine Zahl :min und :max sein.',
        'file'    => ':attribute muss zwischen :min und :max kB groß sein.',
        'string'  => ':attribute muss zwischen :min und :max Zeichen lang sein.',
        'array'   => ':attribute muss zwischen :min und :max Einträge enthalten.',
    ],
    'boolean'        => ':attribute muss Wahr oder Falsch sein.',
    'confirmed'      => ':attribute bestätigung stimmt nicht überein.',
    'date'           => ':attribute ist kein gültiges Datum.',
    'date_equals'    => ':attribute muss ein Datum sein, welches :date entspricht.',
    'date_format'    => ':attribute entspricht nicht dem Format: :format.',
    'different'      => ':attribute und :other müssen sich unterscheiden.',
    'digits'         => ':attribute muss :digits Nachkommastellen haben.',
    'digits_between' => ':attribute muss zwischen :min und :max Nachkommastellen haben.',
    'dimensions'     => ':attribute hat das falsche Seitenverhältnis.',
    'distinct'       => ':attribute ist ein doppelter Eintrag.',
    'email'          => ':attribute muss eine gültige Emailadresse sein.',
    'ends_with'      => ':attribute muss mit einem der folgenden enden: :values',
    'exists'         => 'Das gewählte Feld :attribute ist ungültig.',
    'file'           => ':attribute muss eine Datei sein.',
    'filled'         => ':attribute muss einen Wert enthalten.',
    'gt'             => [
        'numeric' => ':attribute muss größer als :value sein.',
        'file'    => 'Die Dateigröße von :attribute muss größer :value kilobytes sein.',
        'string'  => ':attribute muss mehr als :value Zeichen lang sein.',
        'array'   => ':attribute muss mehr als :value Einträge enthalten.',
    ],
    'gte' => [
        'numeric' => ':attribute muss größer oder gleich :value sein.',
        'file'    => 'Die Dateigröße von :attribute muss größer oder gleich :value kilobytes sein.',
        'string'  => ':attribute muss größer oder gleich :value Zeichen lang sein.',
        'array'   => ':attribute muss :value oder mehr Einträge enthalten.',
    ],
    'image'    => ':attribute muss ein Bild sein.',
    'in'       => 'Das gewählte Feld :attribute ist ungültig.',
    'in_array' => ':attribute existiert nicht in :other.',
    'integer'  => ':attribute muss ein Integer sein.',
    'ip'       => ':attribute muss eine gültige IP Adresse sein.',
    'ipv4'     => ':attribute muss eine gültige IPv4 Adresse sein.',
    'ipv6'     => ':attribute muss eine gültige IPv6 Adresse sein.',
    'json'     => ':attribute muss ein gültiger JSON-String sein.',
    'lt'       => [
        'numeric' => ':attribute muss kleiner als :value sein.',
        'file'    => ':attribute muss weniger als :value kilobytes sein.',
        'string'  => ':attribute muss weniger als :value Zeichen lang sein.',
        'array'   => ':attribute muss weniger als :value Einträge enthalten.',
    ],
    'lte' => [
        'numeric' => ':attribute muss gleich oder weniger :value sein.',
        'file'    => ':attribute muss gleich oder weniger :value kilobytes sein.',
        'string'  => ':attribute muss gleich oder weniger :value Zeichen lang sein.',
        'array'   => ':attribute darf nicht mehr als :value Einträge enthalten.',
    ],
    'max' => [
        'numeric' => ':attribute darf nicht größer als :max sein.',
        'file'    => ':attribute darf nicht größer als :max kilobytes sein.',
        'string'  => ':attribute darf nicht mehr als :max Zeichen lang sein.',
        'array'   => ':attribute darf nicht mehr als :max Einträge enthalten.',
    ],
    'mimes'     => ':attribute muss eine Datei foglender Typen sein: :values.',
    'mimetypes' => ':attribute muss eine Datei foglender Typen sein: :values.',
    'min'       => [
        'numeric' => ':attribute muss mindestens :min entsprechen.',
        'file'    => ':attribute muss mindestens :min kilobytes groß sein.',
        'string'  => ':attribute muss mindestens :min Zeichen lang sein.',
        'array'   => ':attribute muss mindestens :min Einträge enthalten.',
    ],
    'not_in'               => 'Das wählte Feld :attribute ist ungültig.',
    'not_regex'            => ':attribute Format ist ungültig.',
    'numeric'              => ':attribute muss eine Zahl sein.',
    'present'              => ':attribute Feld muss vorhanden sein.',
    'regex'                => ':attribute Format ist ungültig.',
    'required'             => ':attribute ist notwendig.',
    'required_if'          => ':attribute ist notwendig wenn :other :value entspricht.',
    'required_unless'      => ':attribute ist notwendig außer :other ist in :values.',
    'required_with'        => ':attribute ist notwendig wenn :values vorhanden ist.',
    'required_with_all'    => ':attribute ist notwendig wenn :values vorhanden sind.',
    'required_without'     => ':attribute ist notwendig wenn :values nicht vorhanden ist.',
    'required_without_all' => ':attribute ist notwendig wenn keiner von :values vorhanden sind.',
    'same'                 => ':attribute und :other müssen einander entsprechen.',
    'size'                 => [
        'numeric' => ':attribute muss :size sein.',
        'file'    => ':attribute muss :size Kilobytes groß sein.',
        'string'  => ':attribute muss :size Zeichen lang sein.',
        'array'   => ':attribute muss :size Einträge enthalten.',
    ],
    'starts_with' => ':attribute muss mit einem der Folgenden beginnen: :values',
    'string'      => ':attribute muss eine Zeichenkette sein.',
    'timezone'    => ':attribute muss eine gültige Zeitzone sein.',
    'unique'      => ':attribute existiert bereits.',
    'uploaded'    => ':attribute konnte nicht hochgeladen werden.',
    'url'         => ':attribute Format ist ungültig.',
    'uuid'        => ':attribute muss eine gültige UUID sein.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'phone' => [
            'valid-DE-number' => 'Diese Telefonnummer ist nicht gültig.',
            'unique_number'   => 'Diese Telefonnummer ist bereits vorhanden.',
            'unique_lead_phone'   => 'Dieser Lead ist bereits vorhanden. Konflikt anhand der Telefonnummer erkannt.',
            'unique_location_phone'   => 'Diese Location ist bereits vorhanden. Konflikt anhand der Telefonnummer erkannt.'
        ],
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],
];
