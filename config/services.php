<?php
return [
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'talentbridge' => [
        'cid'           => '1',
        'secret_key'    =>'ebd652e7987422c4f1a606db2a7c5e6a29b573a7',
        'url'           => 'http://www.my-gpi.biz/mygpi/iIntegrationAction?'
    ]
];