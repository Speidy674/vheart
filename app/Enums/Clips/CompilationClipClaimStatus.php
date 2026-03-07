<?php

declare(strict_types=1);

namespace App\Enums\Clips;

use App\Enums\Traits\HasHeadlineLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CompilationClipClaimStatus: int implements HasColor, HasLabel
{
    use HasHeadlineLabel;

    case Pending = 0;
    case InProgress = 1;
    case Completed = 2;

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'gray',
            self::InProgress => 'warning',
            self::Completed => 'success',
        };
    }
}
