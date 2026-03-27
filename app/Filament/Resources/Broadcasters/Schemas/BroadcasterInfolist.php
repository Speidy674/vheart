<?php

declare(strict_types=1);

namespace App\Filament\Resources\Broadcasters\Schemas;

use Filament\Schemas\Schema;

class BroadcasterInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }
}
