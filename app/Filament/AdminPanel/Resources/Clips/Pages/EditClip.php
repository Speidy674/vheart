<?php

declare(strict_types=1);

namespace App\Filament\AdminPanel\Resources\Clips\Pages;

use App\Filament\AdminPanel\Resources\Clips\Actions\Moderation\FlagClipAction;
use App\Filament\AdminPanel\Resources\Clips\Actions\Moderation\UnflagClipAction;
use App\Filament\AdminPanel\Resources\Clips\ClipResource;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditClip extends EditRecord
{
    protected static string $resource = ClipResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('admin/resources/clips.edit.title', [
            'label' => $this->getRecordTitle(),
            'broadcaster' => $this->getRecord()->broadcaster?->name,
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            FlagClipAction::make(),
            UnflagClipAction::make(),
            ActionGroup::make([
                RestoreAction::make(),
                DeleteAction::make(),
            ]),
        ];
    }
}
