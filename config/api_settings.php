<?php

return [
    'fields'    => [
        'locationName',
        'websiteUrl',
        'primaryPhone',
        'address',
        'regularHours',
        'specialHours',
        'additionalPhones',
        'primaryCategory',
        'profile',
    ],
    'read_only' => env('GMB_API_READ_ONLY', true),
    'lli_jobs_enabled' => env('LLI_JOBS_ENABLED', false),
];
