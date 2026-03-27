<?php

declare(strict_types=1);

use Carbon\CarbonInterval;

return [
    'clips' => [
        'submission' => [
            // Minimum Clip length we accept at submission
            'minimum_length' => (int) env('VHEART_CLIPS_SUBMISSION_MINIMUM_LENGTH', 5),
            // Maximum Clip age we accept at submission
            'maximum_age' => CarbonInterval::fromString((string) env('VHEART_CLIPS_SUBMISSION_MAXIMUM_AGE', '6 months')),
        ],
        'voting' => [
            'maximum_age' => CarbonInterval::fromString((string) env('VHEART_CLIPS_VOTING_MAXIMUM_AGE', '6 months')),
        ],
    ],
];
