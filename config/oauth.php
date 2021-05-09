<?php
return [
    'goolge' => [
        'web' => [
            'client_id'                   => '268570839915-lnfg91766dhdc5oappgcdrpr8d9lefqb.apps.googleusercontent.com',
            'project_id'                  => 'mybusiness-experts',
            'auth_uri'                    => 'https://accounts.google.com/o/oauth2/auth',
            'token_uri'                   => 'https://oauth2.googleapis.com/token',
            'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
            'redirect_uris'               => [
                0 => 'https://localsearchsystem.localhero.de/*',
                1 => 'https://localsearchsystem.localhero.de',
                2 => 'https://localsearchsystem.localhero.de/admin/googleoauthreturn/',
                3 => 'https://localsearch.alphatier.de',
                4 => 'https://localsearch.alphatier.de/*',
                5 => 'https://localsearch.alphatier.de/admin/googleoauthreturn/',
                6 => 'https://dev.crm.alphatier.de/googleoauth/',
            ],
            'javascript_origins'          => [
                0 => 'https://localsearchsystem.localhero.de',
                1 => 'https://localsearch.alphatier.de',
                2 => 'https://alphatiercrm.dev',
            ],
        ],
    ],
];
