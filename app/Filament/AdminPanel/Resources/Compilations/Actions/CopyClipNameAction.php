<?php

declare(strict_types=1);

namespace App\Filament\AdminPanel\Resources\Compilations\Actions;

use App\Enums\Filament\LucideIcon;
use App\Models\Clip;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class CopyClipNameAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('admin/resources/compilations.relation_managers.clips.actions.copy_filename')
            ->translateLabel()
            ->icon(LucideIcon::ClipboardList)
            ->color('gray')
            ->tooltip(__('admin/resources/compilations.relation_managers.clips.actions.copy_filename_tooltip'))
            ->action(function (Clip $clip, $livewire): void {
                if (! $clip->owner) {
                    Notification::make()
                        ->title(__('admin/resources/compilations.relation_managers.clips.notifications.filename_copy_failed_title'))
                        ->body(__('admin/resources/compilations.relation_managers.clips.notifications.filename_copy_failed_no_broadcaster'))
                        ->danger()
                        ->send();

                    return;
                }

                // enforce windows friendly file names
                $sanitize = static fn (string $value): string => preg_replace('/[\\/:*?"<>|]+/', '-', $value) |> Str::squish(...);

                $broadcaster = $sanitize($clip->owner->name);
                $cutter = $sanitize($clip->claimer?->name ?? 'Unknown Cutter');
                $clipper = $sanitize($clip->creator?->name ?? 'Unknown Clipper');
                $category = $sanitize($clip->category->title);
                $episode = $sanitize($livewire->getOwnerRecord()?->title ?? 'Unknown Episode');

                $filename = "[$clip->id]{$broadcaster}__{$category}__{$cutter}__{$clipper}__{$episode}.mp4";
                $livewire->js('window.navigator.clipboard.writeText('.json_encode($filename, JSON_THROW_ON_ERROR).');');

                Notification::make()
                    ->title(__('admin/resources/compilations.relation_managers.clips.notifications.filename_copied'))
                    ->body($filename)
                    ->success()
                    ->send();
            });
    }

    public static function getDefaultName(): ?string
    {
        return 'copyClipName';
    }
}
