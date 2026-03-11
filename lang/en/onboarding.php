<?php

declare(strict_types=1);

return [
    'title' => 'Broadcaster Onboarding',
    'heading' => 'Welcome, :username!',
    'setup' => [
        'heading' => 'Lets setup your profile.',
        'consent' => [
            'heading' => 'Content Permission',
            'subheading' => 'We need your consent to be able to use and accept your content for our Compilations, you don\'t have to grant them now though if you just want to look around.',
        ],
        'submissions' => [
            'heading' => 'Clip Submissions',
            'subheading' => 'Who should be able to submit your Clips? We recommend to open it for everyone.',
            'options' => [
                'everyone' => [
                    'label' => 'Everyone',
                    'description' => 'Everyone will be able to Submit your clips.',
                ],
                'vips' => [
                    'label' => 'VIPs',
                    'description' => 'VIPs will be able to Submit your clips.',
                ],
                'mods' => [
                    'label' => 'Moderators',
                    'description' => 'Moderators will be able to Submit your clips.',
                ],
            ],
        ],
        'later' => 'I will decide Later',
        'submit' => 'Save and Continue',
    ],
];
