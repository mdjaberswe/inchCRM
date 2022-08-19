<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'clickatell' => [
        'username' => env('CLICKATELL_USERNAME'),
        'password' => env('CLICKATELL_PASSWORD'),
        'api_id' => env('CLICKATELL_API_ID'),
    ],

    'twilio' => [
        'account_sid' => env('TWILIO_ACCOUNT_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'phone_no' => env('TWILIO_PHONE_NO'),
    ],

    'paypal' => [
        'email' => env('PAYPAL_EMAIL'),
        'mode' => env('PAYPAL_MODE'),
        'sandbox_client_id' => env('PAYPAL_SANDBOX_CLIENT_ID'),
        'sandbox_secret' => env('PAYPAL_SANDBOX_SECRET'),
        'live_client_id' => env('PAYPAL_LIVE_CLIENT_ID'),
        'live_secret' => env('PAYPAL_LIVE_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'mode' => env('STRIPE_MODE'),
        'test_key' => env('STRIPE_TEST_PUBLIC_KEY'),
        'test_secret' => env('STRIPE_TEST_SECRET_KEY'),
        'live_key' => env('STRIPE_LIVE_PUBLIC_KEY'),
        'live_secret' => env('STRIPE_LIVE_SECRET_KEY'),
    ],

    'pusher' => [
        'id' => env('PUSHER_APP_ID'),
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'cluster' => env('PUSHER_CLUSTER'),
    ],
];
