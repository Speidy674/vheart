<?php

declare(strict_types=1);

return [
    'enums' => [
        'report-status' => [
            'pending' => 'Pending',
            'resolved' => 'Resolved',
            'dismissed' => 'Dismissed',
        ],
        'report-reason' => [
            'other' => 'Other',
            'spam' => 'Spam',
            'harassment' => 'Harassment',
            'hate-speech' => 'Hate Speech',
        ],
        'resolve-action' => [
            'other' => 'Other',
            'dismissed' => 'Report Dismissed',
            'content-edited' => 'Content Edited',
            'content-removed' => 'Content Removed',
            'user-banned' => 'User Banned',
        ],
    ],
];
