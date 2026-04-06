<?php

declare(strict_types=1);

namespace App\Services\Twitch\Enums;

use App\Enums\Traits\HasHeadlineLabel;
use Filament\Support\Contracts\HasLabel;

enum TwitchBroadcasterLanguage: string implements HasLabel
{
    use HasHeadlineLabel;

    case German = 'de';
    case English = 'en';
    case Other = 'other';
}
