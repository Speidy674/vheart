<?php

declare(strict_types=1);

namespace App\Support\Attributes;

use App\Enums\PermissionGroup as PermissionGroupEnum;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class PermissionGroup
{
    public function __construct(
        public PermissionGroupEnum $name,
    ) {}
}
