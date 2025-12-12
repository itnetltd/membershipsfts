<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // Flutterwave settings
    'flutterwave' => [
        'public_key'     => env('FLW_PUBLIC_KEY'),
        'secret_key'     => env('FLW_SECRET_KEY'),
        'base_url'       => env('FLW_BASE_URL', 'https://api.flutterwave.com'),
        'webhook_secret' => env('FLW_WEBHOOK_SECRET'),
    ],
// config/services.php

    // ...
    'mtn_momo' => [
        // Your “Collections” product on the MTN Developer portal
        'base_url'            => env('MOMO_BASE_URL', 'https://sandbox.momodeveloper.mtn.com'),
        'subscription_key'    => env('MOMO_SUBSCRIPTION_KEY'),   // Ocp-Apim-Subscription-Key
        'api_user'            => env('MOMO_API_USER'),           // UUID you created
        'api_key'             => env('MOMO_API_KEY'),            // Generated API key
        'target_environment'  => env('MOMO_TARGET_ENV', 'sandbox'),
        'callback_url'        => env('MOMO_CALLBACK_URL', 'https://example.com/momo/callback'),
    ],

    'whatsapp' => [
    'enabled'          => env('WHATSAPP_ENABLED', false),
    'access_token'     => env('WHATSAPP_ACCESS_TOKEN'),
    'phone_number_id'  => env('WHATSAPP_PHONE_NUMBER_ID'),
],

];



