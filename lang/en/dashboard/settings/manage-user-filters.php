<?php

declare(strict_types=1);

return [
    'section' => [
        'description' => '',
    ],
    'table' => [
        'name' => 'User',
        'state' => 'Allowed',
    ],
    'filters' => [
        'state' => [
            'label' => 'Allowed',
            'placeholder' => 'All',
            'true' => 'Only Allowed',
            'false' => 'Only Blocked',
        ],
    ],
];
