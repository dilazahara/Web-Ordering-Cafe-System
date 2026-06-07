<?php

return [

    'server_key'    => env('MIDTRANS_SERVER_KEY', ''),
    'client_key'    => env('MIDTRANS_CLIENT_KEY', ''),

    // false = sandbox/testing, true = production
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    // URL Snap.js — sandbox
    'snap_url'      => env(
        'MIDTRANS_SNAP_URL',
        'https://app.sandbox.midtrans.com/snap/snap.js'
    ),
];