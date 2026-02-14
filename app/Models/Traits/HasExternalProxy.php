<?php

declare(strict_types=1);

namespace App\Models\Traits;

trait HasExternalProxy
{
    public static function supportsProxyDynamicSize(): bool
    {
        return false;
    }

    public static function getProxyIdentifierColumn(): string
    {
        return 'id';
    }

    public function toProxyUrl(?int $width = null, ?int $height = null): ?string
    {
        // We are just the contract placeholder in case we have custom logic for a model
        return $this->generateExternalProxyUrl($width, $height);
    }

    /**
     * Generates the route to the external proxy
     */
    protected function generateExternalProxyUrl(?int $width = null, ?int $height = null): ?string
    {
        if (! $this->exists) {
            return null;
        }

        $identifierColumn = self::getProxyIdentifierColumn();

        $identifier = $this->getAttribute($identifierColumn);

        if ($width && $height && self::supportsProxyDynamicSize()) {
            $identifier = "{$identifier}-{$width}x{$height}";
        }

        return route('static-external', [
            'type' => $this->getProxyType(),
            'identifier' => $identifier,
            'extension' => self::getProxyExtension(),
        ]);
    }
}
