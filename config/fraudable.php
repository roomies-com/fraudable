<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AWS Fraud Detector Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the credentials for accessing AWS Fraud Detector, should they
    | differ to your regular application AWS credentials.
    |
    */

    'aws' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Event Label Names
    |--------------------------------------------------------------------------
    |
    | Configure the names used for legitimate and fraudulent events should you
    | select alternative defaults.
    |
    */

    'legitimate_label' => env('FRAUDABLE_LEGITIMATE_LABEL', 'legitimate'),

    'fraudulent_label' => env('FRAUDABLE_FRAUDULENT_LABEL', 'fraudulent'),

    /*
    |--------------------------------------------------------------------------
    | Detector Aliases
    |--------------------------------------------------------------------------
    |
    | Configure human-readable aliases for detectors to use in place of ...
    |
    */

    'detectors' => [

        'default' => env('FRAUDABLE_DETECTOR'),

    ],

];
