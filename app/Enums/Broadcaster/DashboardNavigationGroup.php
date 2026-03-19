<?php

declare(strict_types=1);

namespace App\Enums\Broadcaster;

use App\Enums\Traits\HasTranslatedLabel;
use Filament\Support\Contracts\HasLabel;

enum DashboardNavigationGroup implements HasLabel
{
    use HasTranslatedLabel;

    case Settings;

    private function getTranslatableEnumLabelPrefix(): string
    {
        return 'broadcaster.enums';
    }
}
