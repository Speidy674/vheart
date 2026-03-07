<?php

declare(strict_types=1);

namespace App\Support\FeatureFlag\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class Issue
{
    public function __construct(
        public string|int $issue,
    ) {}
}
