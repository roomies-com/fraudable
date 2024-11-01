<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Identification Service
    |--------------------------------------------------------------------------
    |
    | This option controls the default identification service when using this
    | feature. You can swap this service on the fly if required.
    |
    | Supported services: "aws", "null"
    |
    */
    'default' => env('FRAUDABLE_SERVICE', 'aws'),

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
    | Queue Ingesting
    |--------------------------------------------------------------------------
    |
    | Configure the name of the queue jobs should be dispatched onto.
    |
    */

    'queue' => env('FRAUDABLE_QUEUE', null),

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

];
