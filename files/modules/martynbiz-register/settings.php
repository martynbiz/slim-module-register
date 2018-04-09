<?php
return [
    'settings' => [

        // Renderer settings
        'renderer' => [
            'folders' => [
                'martynbiz-register' => APPLICATION_PATH . '/modules/martynbiz-register/templates',
            ],
        ],

        'register' => [

            'base_path' => '/register',

            'redirect_after_register' => 'console_home',
            'redirect_after_login' => 'console_home',
            'redirect_after_logout' => 'auth_session_login',

            // this is the session namespace. apps that want to authenticate
            // using this auth app must configure their mwauth-client to match
            'namespace' => 'slim3__auth__',

            // remember me cookie settings
            'auth_token' => [
                'cookie_name' => 'auth_token',
                'expire' => strtotime("+3 months", time()), // seconds from now
                'path' => '/',
            ],

            // remember me cookie settings
            'recovery_token' => [
                'expire' => strtotime("+1 hour", time()), // seconds from now
            ],

            // these are attributes that will be written to session
            'valid_attributes' => [
                'id',
                'first_name',
                'last_name',
                'name',
                'email',
                'facebook_id',
            ],
        ],
    ],
];
