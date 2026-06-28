<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Delivery Charges (BDT)
    |--------------------------------------------------------------------------
    |
    | Flat delivery fees based on whether the customer is inside Dhaka.
    |
    */

    'delivery' => [
        'dhaka_district' => 'Dhaka',
        'inside_dhaka' => 60,
        'outside_dhaka' => 120,
    ],

    /*
    |--------------------------------------------------------------------------
    | Bangladesh Districts
    |--------------------------------------------------------------------------
    */

    'districts' => [
        'Dhaka',
        'Chattogram',
        'Sylhet',
        'Khulna',
        'Rajshahi',
        'Barishal',
        'Rangpur',
        'Mymensingh',
    ],

];
