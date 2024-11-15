<?php

return [
    /*
    |--------------------------------------------------------------------------
    | URL Prefix Configuration
    |--------------------------------------------------------------------------
    |
    | This value is the prefix that will be used for all evolve-ui routes.
    | For example, if your prefix is 'admin', URLs will start with '/admin/evolve'.
    |
    */
    'prefix' => env('EVOLVE_UI_PREFIX', ''),

    /*
    |--------------------------------------------------------------------------
    | Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be assigned to every Evolve UI route.
    |
    */
    'middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | Per Page Configuration
    |--------------------------------------------------------------------------
    |
    | Number of items to show per page in index views
    |
    */
    'per_page' => 10,

    /*
    |--------------------------------------------------------------------------
    | View Customization
    |--------------------------------------------------------------------------
    |
    | Configure view related settings
    |
    */
    'views' => [
        'layout' => 'layouts.app', // The layout view to extend
    ],
];
