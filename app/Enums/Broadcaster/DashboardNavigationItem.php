<?php

declare(strict_types=1);

namespace App\Enums\Broadcaster;

use App\Enums\Traits\HasTranslatedLabel;
use Filament\Support\Contracts\HasLabel;

enum DashboardNavigationItem implements HasLabel
{
    use HasTranslatedLabel;

    case ManageConsent;

    private function getTranslatableEnumLabelPrefix(): string
    {
        return 'broadcaster.enums';
    }
}
