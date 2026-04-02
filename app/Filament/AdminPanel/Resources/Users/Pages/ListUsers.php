<?php

declare(strict_types=1);

namespace App\Filament\AdminPanel\Resources\Users\Pages;

use App\Filament\AdminPanel\Resources\Users\UserResource;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
