<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration settings for the API.
    |
    */

    'version' => env('API_VERSION', 'v1'),
    'prefix' => env('API_PREFIX', 'api'),
    'domain' => env('API_DOMAIN', null),
    'debug' => env('API_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | API Throttling
    |--------------------------------------------------------------------------
    |
    | Configure the rate limiting for the API.
    |
    */
    'throttle' => [
        'enabled' => true,
        'attempts' => env('API_THROTTLE_ATTEMPTS', 60),
        'decay_minutes' => env('API_THROTTLE_DECAY_MINUTES', 1),
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported API Versions
    |--------------------------------------------------------------------------
    |
    | List of supported API versions.
    |
    */
    'supported_versions' => [
        'v1',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Response
    |--------------------------------------------------------------------------
    |
    | Configure the default response format.
    |
    */
    'response' => [
        'include_meta' => true,
        'include_timestamp' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | API Documentation
    |--------------------------------------------------------------------------
    |
    | Configure the API documentation settings.
    |
    */
    'documentation' => [
        'enabled' => true,
        'route' => '/documentation',
        'middleware' => ['api'],
    ],
]; 