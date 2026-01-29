<?php

declare(strict_types=1);

return [
    'enums' => [
        'report-status' => [
            'pending' => 'In Bearbeitung',
            'resolved' => 'Fertiggestellt',
            'dismissed' => 'Verworfen',
        ],
        'report-reason' => [
            'other' => 'Anderes',
            'spam' => 'Spam',
            'harassment' => 'Belästigung',
            'hate-speech' => 'Hassrede',
        ],
        'resolve-action' => [
            'other' => 'Anderes',
            'dismissed' => 'Report Verworfen',
            'content-edited' => 'Inhalte Angepasst',
            'content-removed' => 'Inhalte entfernt',
            'user-banned' => 'Benutzer Gesperrt',
        ],
    ],
];
