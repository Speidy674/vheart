<?php

declare(strict_types=1);

namespace App\Filament\AdminPanel\Resources\FaqEntries\Pages;

use App\Filament\AdminPanel\Resources\FaqEntries\FaqEntryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ViewRecord\Concerns\Translatable;

class ViewFaqEntry extends ViewRecord
{
    use Translatable;

    protected static string $resource = FaqEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            EditAction::make(),
        ];
    }
}
