<?php

declare(strict_types=1);

return [
    'table' => [
        'columns' => [
            'question' => [
                'label' => 'Question',
                'placeholder' => 'No Value for Selected Language',
            ],
            'published_at' => [
                'label' => 'Published At',
                'placeholder' => 'Not Published',
            ],
        ],
    ],
    'form' => [
        'question' => [
            'label' => 'Question',
            'placeholder' => 'No Value for Selected Language',
        ],
        'published_at' => [
            'label' => 'Published At',
            'placeholder' => 'Not Published',
        ],
        'body' => [
            'label' => 'Answer',
            'hint' => 'Required if Title was set.',
            'placeholder' => 'No Value for Selected Language',
        ],
    ],
];
