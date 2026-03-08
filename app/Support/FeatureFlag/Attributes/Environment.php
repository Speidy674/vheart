<?php

declare(strict_types=1);

namespace App\Support\FeatureFlag\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class Environment
{
    /**
     * Disables the Feature flag if the Current environment differs from the set value
     */
    public function __construct(
        public string|array $state,
    ) {}
}
