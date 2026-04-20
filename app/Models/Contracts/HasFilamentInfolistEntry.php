<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use Filament\Infolists\Components\Entry;

interface HasFilamentInfolistEntry
{
    public static function getFilamentInfolistEntry(string $name): Entry;
}
