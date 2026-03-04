<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\FeatureFlagMagic;
use App\Enums\Traits\HasHeadlineLabel;
use App\Support\FeatureFlag\Attributes\DefaultFeatureFlagState;
use App\Support\FeatureFlag\Attributes\Description;
use App\Support\FeatureFlag\Attributes\Issue;
use Filament\Support\Contracts\HasLabel;

enum FeatureFlag: string implements HasLabel
{
    use FeatureFlagMagic;
    use HasHeadlineLabel;

    #[Description('Example Feature Flag')]
    #[Issue(1)]
    case PlaceHolder = 'placeholder';

    #[DefaultFeatureFlagState(true)]
    case DefaultEnabledFlag = 'default_enabled_flag';
}
