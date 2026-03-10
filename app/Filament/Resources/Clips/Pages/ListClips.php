<?php

declare(strict_types=1);

namespace App\Filament\Resources\Clips\Pages;

use App\Filament\Actions\Tables\SubmitClipAction;
use App\Filament\Resources\Clips\ClipResource;
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
