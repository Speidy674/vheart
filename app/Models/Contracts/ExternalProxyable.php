<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use App\Enums\ExternalContentProxyType;

/**
 * Allows this to be Proxied via our External Content Proxy
 */
interface ExternalProxyable
{
    /**
     * The database column used to identify this resource (e.g., 'id' or 'twitch_id').
     */
    public static function getProxyIdentifierColumn(): string;

    /**
     * The database column containing the remote URL (e.g., 'avatar_url').
     */
    public static function getProxyUrlColumn(): string;

    /**
     * What extension do we expect for this proxied content
     */
    public static function getProxyExtension(): string;

    /**
     * Does this content support dynamic sizing
     */
    public static function supportsProxyDynamicSize(): bool;

    public function getProxyType(): ExternalContentProxyType;

    /**
     * Get the route to the external proxy for this content
     */
    public function proxiedContentUrl(?int $width = null, ?int $height = null): ?string;
}
