<?php

declare(strict_types=1);

return [
    'title' => 'Dashboard',
    'description' => 'Verwalte deine Clips und Berechtigungen',
    'nav' => [
        'manage_clips' => 'Clips verwalten',
        'permissions' => 'Berechtigungen',
    ],
    'permissions' => [
        'title' => 'Berechtigungen',
        'description' => 'Verwalte, welche Berechtigungen du VHeart erteilst.',
        'clip_title' => 'Clip-Berechtigung',
        'clip_description' => 'Erlaube uns, deine eingerichteten Clips zu nutzen.',
        'clip_disclaimer' => 'Dein Entzug der Berechtigung nach Mittwoch 0:00 Uhr wird ggf. erst für die übernächste Folge wirksam, da die aktuelle Produktion zu diesem Zeitpunkt bereits abgeschlossen sein kann.',
        'granted' => 'Berechtigung erteilt',
        'revoked' => 'Nicht erteilt',
        'toggle_label' => 'Clip-Nutzung erlauben',
    ],
];
