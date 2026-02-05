<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

trait HasTranslatedLabel
{
    /**
     * Returns a translated string based on this format:
     *
     * `<prefix>.<kebab case enum class name>.<lowercase case name>`
     *
     * Example key with `CollectionStatus->Internal` and default prefix:
     *
     * `enums.collection-status.internal`
     */
    public function getLabel(): string|Htmlable|null
    {
        $enumClassName = Str::kebab(class_basename(static::class));
        $enumValueName = Str::kebab($this->name);

        return __("{$this->getTranslatableEnumLabelPrefix()}.{$enumClassName}.{$enumValueName}");
    }

    /**
     * Returns the prefix of the translation path
     * e.g. "enums" will result in `enums.<enum>.<name>`
     */
    private function getTranslatableEnumLabelPrefix(): string
    {
        return 'enums';
    }
}
