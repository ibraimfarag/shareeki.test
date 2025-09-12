<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => 'https://shareeki.net/auth/google/callback',
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => 'https://shareeki.net/auth/facebook/callback',
    ],

    'sms' => [
        'enabled' => env('SMS_ENABLED', true),
        'client_id' => env('SMS_CLIENT_ID', 'db063035104d84fa2997e7bb57b98cad'),
        'client_secret' => env('SMS_CLIENT_SECRET', '62bb979cd02dc6ced1b8c25b02d2e878101ab9d8b9f3cfddafbb33b0706c00a5'),
        'username' => env('SMS_USERNAME', 'Shareeki2030'),
        'api_url' => env('SMS_API_URL', 'https://www.dreams.sa/index.php/api/sendsms'),
        'senders' => [
            'default' => env('SMS_SENDER_DEFAULT', 'Shareeki'),
            'ads' => env('SMS_SENDER_ADS', 'Shareeki-AD'),
        ],
    ],

    'recaptcha' => [
        'site' => env('RECAPTCHA_SITE'),
        'secret' => env('RECAPTCHA_SECRET'),
    ],

];
