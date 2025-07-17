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

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'email' => [
        'base_url' => env('EMAIL_SERVICE_URL', 'https://api.sendgrid.com/v3/'),
        'api_key' => env('EMAIL_SERVICE_API_KEY'),
        'from_address' => env('EMAIL_FROM_ADDRESS', 'noreply@example.com'),
        'from_name' => env('EMAIL_FROM_NAME', 'Sistema'),
        'admin_email' => env('ADMIN_EMAIL', 'admin@example.com'),
    ],

    'messaging' => [
        'base_url' => env('MESSAGING_SERVICE_URL', 'https://api.twilio.com/2010-04-01/'),
        'account_sid' => env('MESSAGING_ACCOUNT_SID'),
        'auth_token' => env('MESSAGING_AUTH_TOKEN'),
        'from_number' => env('MESSAGING_FROM_NUMBER'),
        'whatsapp_number' => env('MESSAGING_WHATSAPP_NUMBER'),
        'admin_phone' => env('ADMIN_PHONE'),
    ],

    'analytics' => [
        'base_url' => env('ANALYTICS_SERVICE_URL', 'https://www.googleapis.com/analytics/v3/'),
        'tracking_id' => env('ANALYTICS_TRACKING_ID'),
        'view_id' => env('ANALYTICS_VIEW_ID'),
        'access_token' => env('ANALYTICS_ACCESS_TOKEN'),
    ],
];
