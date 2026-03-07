<?php

declare(strict_types=1);

return [
    'form' => [
        'title' => 'Title',
        'twitch_category' => 'Twitch Category',
        'duration' => 'Duration',
        'broadcaster' => 'Broadcaster',
        'creator' => 'Clip Creator',
        'submitted_by' => 'Submitted By',
        'category' => 'Category',
        'created_at' => 'Clip Created At',
        'tags' => 'Tags',
    ],
    'infolist' => [
        'title' => 'Title',
        'category' => 'Category',
        'duration' => 'Duration',
        'broadcaster' => 'Broadcaster',
        'creator' => 'Clip Creator',
        'submitted_by' => 'Submitted By',
        'submitted_at' => 'Submitted At',
        'created_at' => 'Clip Created At',
    ],
    'table' => [
        'columns' => [
            'twitch_id' => 'Twitch ID',
            'thumbnail' => 'Thumbnail',
            'title' => 'Title',
            'broadcaster' => 'Broadcaster',
            'creator' => 'Clip Creator',
            'submitter' => 'Submitter',
            'category' => 'Category',
            'duration' => 'Duration',
            'status' => 'Status',
            'votes' => 'Votes',
            'submitted_at' => 'Submitted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ],
    ],
    'filters' => [
        'broadcaster' => 'Broadcaster',
        'creator' => 'Clip Creator',
        'submitter' => 'Submitter',
        'category' => 'Category',
        'tags' => 'Tags',
        'status' => 'Status',
        'status_visibility' => [
            'label' => 'Status',
            'placeholder' => 'All',
            'true' => 'Blocked Only',
            'false' => 'Approved Only',
        ],

        'created_range' => [
            'label' => 'Created Between',
            'form' => [
                'from' => 'From',
                'to' => 'To',
            ],
            'actions' => [
                'clear_from' => 'Clear From',
                'clear_to' => 'Clear To',
            ],
            'indicators' => [
                'from' => 'Created After: :value',
                'to' => 'Created Before: :value',
            ],
        ],

        'submission_range' => [
            'label' => 'Submitted Between',
            'form' => [
                'from' => 'From',
                'to' => 'To',
            ],
            'actions' => [
                'clear_from' => 'Clear From',
                'clear_to' => 'Clear To',
            ],
            'indicators' => [
                'from' => 'Submitted After: :value',
                'to' => 'Submitted Before: :value',
            ],
        ],

        'date_range_presets' => [
            'label' => 'Presets',
            'options' => [
                'today' => 'Today',
                'last_7_days' => 'Last 7 Days',
                'last_30_days' => 'Last 30 Days',
                'last_90_days' => 'Last 90 Days',
                'this_month' => 'This Month',
                'last_month' => 'Last Month',
            ],
        ],
    ],
    'edit' => [
        'title' => 'Edit :label by :broadcaster',
    ],
    'actions' => [
        'download' => 'Open Downloadable Clip',
        'view_on_twitch' => 'View On Twitch',
        'attach_to_compilation' => [
            'label' => 'Attach to Compilation',
            'claim' => 'Claim Clip',
            'status' => 'Clip Status',
        ],
    ],
    'notifications' => [
        'actions' => [
            'attached_to_compilation' => 'Attached to Compilation',
        ],
    ],
];
