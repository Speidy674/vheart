<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\HasHeadlineLabel;
use Filament\Support\Contracts\HasLabel;

enum PermissionGroup implements HasLabel
{
    use HasHeadlineLabel;

    case Other;
}
