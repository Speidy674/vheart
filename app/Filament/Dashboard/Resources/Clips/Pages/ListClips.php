<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Clips\Pages;

use App\Filament\Dashboard\Resources\Clips\ClipResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClips extends ListRecords
{
    protected static string $resource = ClipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
