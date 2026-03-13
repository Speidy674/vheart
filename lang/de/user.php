<?php

declare(strict_types=1);

return [
    'settings' => [
        'title' => 'Account Settings',
        'broadcaster_note' => [
            'label' => 'Hinweis für Broadcaster',
            'description' => 'Um spezifische Einstellungen für deinen Kanal zu verwalten, wechsle bitte in das Broadcaster Dashboard.',
            'link' => 'Zum Broadcaster Dashboard',
        ],
        'delete' => [
            'heading' => 'Konto Löschen',
            'subheading' => 'Möchtest du dein Konto wirklich löschen?',
            'description' => 'Dein Broadcaster-Profil und persönliche Daten werden gelöscht. Aus technischen Gründen bleiben deine Twitch-Nutzer-ID, von dir abgegebene Stimmen bis zur Archivierung sowie von dir eingereichte Clips anderer Kanäle erhalten. Clips von deinem eigenen Kanal werden jedoch sofort ausgeblendet und erst wieder sichtbar, falls du in Zukunft ein neues Broadcaster-Profil erstellst.',
            'confirmation' => [
                'two-factor' => [
                    'label' => 'Gib zur Bestätigung deinen Zwei-Faktor Code ein',
                ],
                'keyword' => [
                    'label' => 'Gib zur Bestätigung <code>:keyword</code> ein.',
                    'keyword' => 'KONTO LOESCHEN',
                ],
            ],
            'submit' => 'Konto Löschen',
        ],
    ],
];
