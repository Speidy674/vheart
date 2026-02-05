<?php

declare(strict_types=1);

namespace App\Services\Twitch\Enums;

use App\Enums\Traits\HasTranslatedLabel;
use Filament\Support\Contracts\HasLabel;

enum TwitchUserType: string implements HasLabel
{
    use HasTranslatedLabel;

    case User = ''; // I hate twitch for that
    case Staff = 'staff';
    case GlobalModerator = 'global_mod';
    case Administrator = 'admin';
}
