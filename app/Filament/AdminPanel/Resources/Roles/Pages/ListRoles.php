<?php

declare(strict_types=1);

namespace App\Filament\AdminPanel\Resources\Roles\Pages;

use App\Filament\AdminPanel\Resources\Roles\RoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ListRoles extends ListRecords
{
    use Translatable;

    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            CreateAction::make(),
        ];
    }
}
