<?php

declare(strict_types=1);

namespace App\Filament\AdminPanel\Resources\Users\Schemas;

use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }
}
