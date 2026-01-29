<?php

declare(strict_types=1);

namespace App\Enums\Reports;

use App\Enums\Traits\HasTranslatedLabel;
use Filament\Support\Contracts\HasLabel;

enum ReportStatus: int implements HasLabel
{
    use HasTranslatedLabel;

    case Pending = 0;
    case Resolved = 1;
    case Dismissed = 2;

    private function getTranslatableEnumLabelPrefix(): string
    {
        return 'reports.enums';
    }
}
