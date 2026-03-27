<?php

declare(strict_types=1);

namespace App\Filament\Resources\Broadcasters\Pages;

use App\Filament\Resources\Broadcasters\BroadcasterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBroadcasters extends ListRecords
{
    protected static string $resource = BroadcasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
