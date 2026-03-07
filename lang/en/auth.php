<?php

declare(strict_types=1);

return [
    'account_disabled' => 'Your account has been deactivated. Please contact us if you think this is a mistake.',
    'account_created_too_early' => 'Your Twitch account has been created too early, please try again later.',
    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'verification' => [
        'sent' => 'Email verification link has been sent to your email address.',
    ],
    'two-factor' => [
        'heading' => 'Two-Factor Authentication',
        'subheading' => 'Enter the authentication code provided by your authenticator application, or use an emergency recovery code.',
        'form' => [
            'code' => [
                'label' => 'Authenticator Code',
            ],
            'backup' => [
                'label' => 'Recovery Code',
            ],
            'submit' => 'Continue',
            'mode-toggle' => [
                'otp' => [
                    'label' => 'Use a recovery code',
                ],
                'backup' => [
                    'label' => 'Use an authenticator code',
                ],
            ],
        ],

        'validation' => [
            'otp' => 'The authentication code must be exactly 6 digits.',
            'recovery' => 'The recovery code must be exactly 21 characters (including the hyphen).',
            'incorrect' => 'The provided code is invalid.',
        ],
    ],
];
