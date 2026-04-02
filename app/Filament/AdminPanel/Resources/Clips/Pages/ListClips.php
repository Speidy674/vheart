<?php

declare(strict_types=1);

namespace App\Filament\AdminPanel\Resources\Clips\Pages;

use App\Filament\Actions\Tables\SubmitClipAction;
use App\Filament\AdminPanel\Resources\Clips\ClipResource;
use Filament\Resources\Pages\ListRecords;

class ListClips extends ListRecords
{
    protected static string $resource = ClipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SubmitClipAction::make()->withBypass(),
        ];
    }
}
