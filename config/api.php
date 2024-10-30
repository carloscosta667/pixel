<?php

use Illuminate\Support\ServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | API Message Response
    |--------------------------------------------------------------------------
    |
    | All possible messages from the endpoints
    |
    */

    'service' => [
        'car' => [
            'get' => [
                'success' => 'Get car service successful',
                'error'   => 'Get car service not successful',
            ],
            'post' => [
                'success' => 'Car service successful created',
                'error'   => 'Car service not successful created'
            ],
            'put' => [
                'success' => 'Car service successful edited',
                'error'   => 'Car service not successful edited',
            ],
            'delete' => [
                'success' => 'Car service successful deleted',
                'error'   => 'Car service not successful deleted'
            ]
        ]
    ],

    'something_wrong' => 'Something went wrong!',

    'account_not_activated' => 'Account not activated',
    'login_success' => 'Successful logged',
    'login_invalid' => 'Invalid login',

];
