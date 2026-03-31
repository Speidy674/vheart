<?php

declare(strict_types=1);

namespace App\Filament\Resources\Broadcasters\Pages;

use App\Filament\Resources\Broadcasters\BroadcasterResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBroadcaster extends EditRecord
{
    protected static string $resource = BroadcasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
