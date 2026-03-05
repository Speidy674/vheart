<?php

declare(strict_types=1);

return [
    'account_disabled' => 'Dein Konto wurde Deaktiviert. Wenn du denkst, es sei ein fehler, bitte kontaktiere uns.',
    'account_created_too_early' => 'Dein Twitch Konto ist zu neu, bitte warte und versuche es später erneut.',
    'throttle' => 'Zu viele Login-Versuche. Bitte versuche es in :seconds Sekunden erneut.',
    'verification' => [
        'sent' => 'Ein Link zur E-Mail-Verifizierung wurde an deine E-Mail-Adresse gesendet.',
    ],
    'two-factor' => [
        'heading' => 'Zwei-Faktor-Authentifizierung',
        'subheading' => 'Gib den Authentifizierungscode deiner Authentifikator-App ein oder verwende einen Wiederherstellungscode.',
        'form' => [
            'code' => [
                'label' => 'Authentifikator-Code',
            ],
            'backup' => [
                'label' => 'Wiederherstellungscode',
            ],
            'submit' => 'Weiter',
            'mode-toggle' => [
                'otp' => [
                    'label' => 'Einen Wiederherstellungscode verwenden',
                ],
                'backup' => [
                    'label' => 'Einen Authentifikator-Code verwenden',
                ],
            ],
        ],

        'validation' => [
            'otp' => 'Der Authentifizierungscode muss genau 6 Ziffern lang sein.',
            'recovery' => 'Der Wiederherstellungscode muss genau 21 Zeichen lang sein (einschließlich Bindestrich).',
            'incorrect' => 'Der angegebene Code ist ungültig.',
        ],
    ],
];
