<?php

/*
 * You can place your custom package configuration in here.
 */
return [

    /**
     * The SPA url. It will be used to set the allowed origins in the CORS config as well as the redirect paths for Fortify
     */
    "spa_url" => env("SPA_URL", env("APP_URL", 'http://localhost')),

    /**
     * Determines whether the email exists endpoint should be registered
     *
     * Some people/companies consider that this could be a security/privacy risk. Others argue that similar information is already available through the registration endpoint or even a password reset. You should decide for yourself if you want to use this endpoint or not.
     *
     * If you enable this endpoint, it will be rate limited just like the login endpoint.
     */
    "check_email_exists_endpoint" => false,

    /**
     * The route paths for Fortify views. Even though we disable the views in this package, sometimes those routes are used in email links, for example.
     */
    "route_paths" => [
        'login' => 'login',
        'register' => 'register',
        'verify_email' => 'email/verify',
        'forgot_password' => 'forgot-password',
        'reset_password' => 'reset-password',
        'confirm_password' => 'user/confirm-password',
        'two_factor_challenge' => 'two-factor-challenge',

        /**
         * If you enable the check_email_exists_endpoint, this is the path that will be used for that endpoint
         */
        'email_exists' => 'email-exists/{email}',
    ]
];
