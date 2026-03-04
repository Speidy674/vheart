<?php

declare(strict_types=1);

return [
    /*
     * Add Feature flag overrides here
     * this will override database values if set to something other than null
     */

    'placeholder' => env('FEATURE_PLACEHOLDER', null),
    'clip_submission' => env('FEATURE_CLIP_SUBMISSION', null),
    'clip_voting' => env('FEATURE_CLIP_VOTING', null),
    'reporting' => env('FEATURE_REPORTING', null),
];
