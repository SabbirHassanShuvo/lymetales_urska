<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    */
    'currency'         => env('SHOP_CURRENCY', 'eur'),
    'currency_symbol'  => env('SHOP_CURRENCY_SYMBOL', '€'),

    /*
    |--------------------------------------------------------------------------
    | Fees
    |--------------------------------------------------------------------------
    */
    'shipping_fee'          => env('SHOP_SHIPPING_FEE', 5.95),
    'fast_production_fee'   => env('SHOP_FAST_PRODUCTION_FEE', 9.95),

    /*
    |--------------------------------------------------------------------------
    | Cities (dropdown on checkout form)
    |--------------------------------------------------------------------------
    */
    'cities' => [
        'Amsterdam',
        'Rotterdam',
        'The Hague',
        'Utrecht',
        'Eindhoven',
        'Groningen',
        'Tilburg',
        'Almere',
        'Breda',
        'Nijmegen',
    ],

    /*
    |--------------------------------------------------------------------------
    | Order Number
    |--------------------------------------------------------------------------
    */
    'order_number_prefix'   => 'LYM',
    'order_number_length'   => 8,   // characters after the prefix-dash
    'order_number_retries'  => 10,

    /*
    |--------------------------------------------------------------------------
    | Session Keys
    |--------------------------------------------------------------------------
    */
    'cart_session_key'   => 'shop_cart',
    'coupon_session_key' => 'shop_coupon',

];
