<?php

declare(strict_types=1);

namespace App\Filament\Resources\Broadcasters\Pages;

use App\Filament\Resources\Broadcasters\BroadcasterResource;
use App\Models\Broadcaster\Broadcaster;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBroadcasters extends ListRecords
{
    protected static string $resource = BroadcasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->after(fn (Broadcaster $record, $livewire) => $livewire->redirect(
                    BroadcasterResource::getUrl('view', ['record' => $record])
                )),
        ];
    }
}
