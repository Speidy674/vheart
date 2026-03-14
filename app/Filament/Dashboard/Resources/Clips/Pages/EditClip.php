<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Clips\Pages;

use App\Filament\Dashboard\Resources\Clips\ClipResource;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditClip extends EditRecord
{
    protected static string $resource = ClipResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('dashboard/resources/clips.edit.title', [
            'label' => $this->getRecordTitle(),
            'broadcaster' => $this->getRecord()->broadcaster?->name,
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                DeleteAction::make(),
            ]),
        ];
    }
}
