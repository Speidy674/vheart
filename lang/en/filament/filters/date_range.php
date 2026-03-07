<?php

declare(strict_types=1);

return [
    'label' => ':name Between',

    'form' => [
        'from' => 'From',
        'to' => 'To',
    ],
    'actions' => [
        'clear_from' => 'Clear From',
        'clear_to' => 'Clear To',
    ],
    'indicators' => [
        'from' => ':name After: :value',
        'to' => ':name Before: :value',
    ],

    'presets' => [
        'label' => 'Presets',
        'default_options' => [
            'today' => 'Today',
            'last_7_days' => 'Last 7 Days',
            'last_30_days' => 'Last 30 Days',
            'last_90_days' => 'Last 90 Days',
            'this_month' => 'This Month',
            'last_month' => 'Last Month',
        ],
    ],
];
