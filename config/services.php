<?php

return [
    'tutor-id' => [
        'diver_name' => env('TUTOR_ID_DIVER_NAME', 'tutor-id'),
        'client_id' => env('TUTOR_ID_CLIENT_ID'),
        'client_secret' => env('TUTOR_ID_CLIENT_SECRET'),
        'redirect' => env('TUTOR_ID_REDIRECT'),
        'url_authorize' => env('TUTOR_ID_URL_AUTHORIZE', 'http://lms-test.edupiatutor.vn/realms/Backend/protocol/openid-connect/auth'),
        'url_get_token' => env('TUTOR_ID_URL_GET_TOKEN', 'http://lms-test.edupiatutor.vn/realms/Backend/protocol/openid-connect/token'),
        'url_get_user_by_token' => env('TUTOR_ID_URL_GET_USER_BY_TOKEN', 'http://lms-test.edupiatutor.vn/realms/Backend/protocol/openid-connect/userinfo'),
        'url_logout' => env('TUTOR_ID_URL_LOGOUT','http://lms-test.edupiatutor.vn/realms/Backend/protocol/openid-connect/logout')
    ]
];
