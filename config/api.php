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
            ],
            'type' => [
                'post' => 'Service type successful linked to the car service',
                'delete' => 'Service type successful unlinked from the car service',
                'already_linked' => 'Service type already linked to the car service'
            ]
        ],

        'type' => [
            'get' => [
                'success' => 'Get service type successful',
                'error'   => 'Get service type not successful',
            ],
            'post' => [
                'success' => 'Service type successful created',
                'error'   => 'Service type not successful created'
            ],
            'put' => [
                'success' => 'Service type successful edited',
                'error'   => 'Service type not successful edited',
            ],
            'delete' => [
                'success' => 'Service type successful deleted',
                'error'   => 'Service type not successful deleted'
            ]
        ]
    ],

    'mechanic' => [
        'get' => [
            'success' => 'Get mechanic successful',
            'error'   => 'Get mechanic not successful',
        ],
        'post' => [
            'success' => 'Mechanic successful created',
            'error'   => 'Mechanic not successful created'
        ],
        'put' => [
            'success' => 'Mechanic successful edited',
            'error'   => 'Mechanic not successful edited',
        ],
        'delete' => [
            'success' => 'Mechanic successful deleted',
            'error'   => 'Mechanic not successful deleted'
        ],
        'unique_name' => 'The first name and last name already taken'
    ],

    'something_wrong' => 'Something went wrong!',

    'account_not_activated' => 'Account not activated',
    'login_success' => 'Successful logged',
    'login_invalid' => 'Invalid login',

];
