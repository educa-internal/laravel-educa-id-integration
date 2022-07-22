<?php

return [
    'tutor-id' => [
        'diver_name' => env('TUTOR_ID_DIVER_NAME', 'tutor-id'),
        'client_id' => env('TUTOR_ID_CLIENT_ID'),
        'client_secret' => env('TUTOR_ID_CLIENT_SECRET'),
        'redirect' => env('TUTOR_ID_REDIRECT'),
        'url_authorize' => env('TUTOR_ID_URL_AUTHORIZE'),
        'url_get_token' => env('TUTOR_ID_URL_GET_TOKEN'),
        'url_get_user_by_token' => env('TUTOR_ID_URL_GET_USER_BY_TOKEN'),
        'url_logout' => env('TUTOR_ID_URL_LOGOUT')
    ]
];

