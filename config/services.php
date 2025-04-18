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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'nyt' => [
        /*
          |--------------------------------------------------------------------------
          | NYT Books API Credentials & Version
          |--------------------------------------------------------------------------
          */
        'key'         => env('NYT_API_KEY', ''),

        /*
        |--------------------------------------------------------------------------
        | Base URL & Version
        |--------------------------------------------------------------------------
        */
        'base_url'    => env('NYT_API_BASE_URL', 'https://api.nytimes.com/svc/books'),
        'version'     => env('NYT_API_VERSION', 'v3'),

        /*
        |--------------------------------------------------------------------------
        | HTTP Client Defaults
        |--------------------------------------------------------------------------
        |
        | timeout_seconds   — how long to wait for a response before giving up
        | retries           — how many times to retry on network/server errors
        | retry_delay_ms    — how long (ms) to wait between retry attempts
        |
        */
        'timeout_seconds'  => env('NYT_HTTP_TIMEOUT',    5),
        'retries'          => env('NYT_HTTP_RETRIES',    3),
        'retry_delay_ms'   => env('NYT_HTTP_RETRY_DELAY', 200),
    ],

];
