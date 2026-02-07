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
        'created_at' => 'Clip Created At',
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
            'is_anonymous' => 'Anonymous',
            'votes_jury' => 'Jury Votes',
            'votes_public' => 'Public Votes',
            'submitted_at' => 'Submitted At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ],
    ],
    'filters' => [
        'broadcaster' => 'Broadcaster',
        'creator' => 'Creator',
        'submitter' => 'Submitter',
        'game' => 'Game',
        'tags' => 'Tags',
        'in_compilation' => [
            'label' => 'Compilations',
            'only_without_compilation' => 'Clips without Compilation',
            'only_with_compilation' => 'Clips with Compilation',
            'with_compilation' => 'All Clips',
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
