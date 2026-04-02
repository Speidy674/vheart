<?php

declare(strict_types=1);

namespace App\Filament\AdminPanel\Resources\Categories\Pages;

use App\Filament\AdminPanel\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
