<?php

return [

    'domain' => [
        'main'      => env('DOMAIN_MAIN', 'https://erp.com'),
    ],

    'service' => [
        // 'main'      => env('SERVICE_MAIN'),
    ],
    
    'limit' => [
        'token'     => env('LIMIT_TOKEN', 24), // hours
        'code'      => env('LIMIT_CODE', 5), // minutes
        'withdraw'  => env('LIMIT_WITHDRAW', 3), // days
    ],

];