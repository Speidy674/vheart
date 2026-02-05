<?php

namespace App\Filament\Resources\Clips\Pages;

use App\Filament\Resources\Clips\ClipResource;
use App\Models\Clip;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewClip extends ViewRecord
{
    protected static string $resource = ClipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('open_twitch')
                ->label(__('admin/resources/clips.actions.view_on_twitch'))
                ->icon(Heroicon::Link)
                ->url(function (Clip $clip) {
                    return $clip->url;
                })
                ->openUrlInNewTab(),
            EditAction::make(),
        ];
    }
}
