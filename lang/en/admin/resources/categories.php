<?php

declare(strict_types=1);

return [
    'table' => [
        'columns' => [
            'box_art' => 'Box Art',
            'title' => 'Title',
            'is_banned' => 'Is Banned',
        ],
        'filters' => [
            'is_banned' => 'Is Banned',
        ],
        'actions' => [
            'ban' => 'Ban Category',
            'unban' => 'Unban Category',
        ],
    ],
];
