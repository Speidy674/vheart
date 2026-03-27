<?php

declare(strict_types=1);

namespace App\Filament\Resources\Compilations\RelationManagers;

use App\Enums\Clips\ClipStatus;
use App\Enums\Clips\CompilationClipClaimStatus;
use App\Enums\Filament\LucideIcon;
use App\Enums\Permission;
use App\Events\Admin\Compilations\CompilationClipClaimed;
use App\Events\Admin\Compilations\CompilationClipStatusUpdated;
use App\Events\Admin\Compilations\CompilationClipUnclaimed;
use App\Filament\Resources\Clips\ClipResource;
use App\Filament\Resources\Clips\Tables\ClipColumns;
use App\Models\Clip;
use App\Models\User;
use App\Services\Twitch\Data\ClipDownloadDto;
use App\Services\Twitch\Exceptions\TwitchApiException;
use App\Services\Twitch\TwitchEndpoints;
use App\Services\Twitch\TwitchService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Kirschbaum\Commentions\Filament\Actions\CommentsAction;
use Livewire\Component;

class ClipsRelationManager extends RelationManager
{
    protected static string $relationship = 'clips';

    protected static ?string $relatedResource = ClipResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with([
                'category',
                'broadcaster',
                'creator',
                'submitter',
                'claimer',
            ]))
            ->recordTitleAttribute('title')
            ->columns([
                Split::make([
                    Stack::make([
                        ClipColumns::thumbnail(),
                    ])->grow(false),

                    Stack::make([
                        ClipColumns::title(),
                        Split::make([
                            ClipColumns::duration(),
                            ClipColumns::juryVotes('votes_jury'),
                            ClipColumns::publicVotes('votes_public'),
                            ClipColumns::status()
                                ->label('admin/resources/compilations.relation_managers.clips.columns.status_moderation')
                                ->tooltip(__('admin/resources/compilations.relation_managers.clips.columns.status_moderation')),
                        ])->grow(false),
                    ])->space(),

                    Stack::make([
                        ClipColumns::broadcasterName(),
                        ClipColumns::creatorName(),
                        ClipColumns::submitterName(),
                    ])
                        ->space(1),

                    Stack::make([
                        ClipColumns::createdAt('clips.date')
                            ->getStateUsing(fn (Clip $clip) => $clip->date),
                        ClipColumns::submittedAt('clips.created_at')
                            ->getStateUsing(fn (Clip $clip) => $clip->created_at),

                        ClipColumns::category(),
                    ])
                        ->space(1),

                    Stack::make([
                        TextColumn::make('adder.name')
                            ->label('admin/resources/compilations.relation_managers.clips.columns.adder')
                            ->tooltip(__('admin/resources/compilations.relation_managers.clips.columns.adder'))
                            ->translateLabel()
                            ->icon(LucideIcon::Plus)
                            ->color('gray'),

                        TextColumn::make('claimer.name')
                            ->label('admin/resources/compilations.relation_managers.clips.columns.claimer')
                            ->tooltip(__('admin/resources/compilations.relation_managers.clips.columns.claimer'))
                            ->translateLabel()
                            ->weight('bold')
                            ->icon(LucideIcon::Check)
                            ->color('gray'),
                        TextColumn::make('claim_status')
                            ->label('admin/resources/compilations.relation_managers.clips.columns.status_cutter')
                            ->tooltip(__('admin/resources/compilations.relation_managers.clips.columns.status_cutter'))
                            ->badge()
                            ->icon(LucideIcon::Clipboard)
                            ->translateLabel(),
                        TextColumn::make('pivot.removed_at')
                            ->label(__('admin/resources/compilations.relation_managers.clips.columns.removed_at'))
                            ->tooltip(__('admin/resources/compilations.relation_managers.clips.columns.removed_at'))
                            ->icon(LucideIcon::Calendar)
                            ->translateLabel()
                            ->dateTime(),
                        TextColumn::make('pivot.added_at')
                            ->label(__('admin/resources/compilations.relation_managers.clips.columns.added_at'))
                            ->tooltip(__('admin/resources/compilations.relation_managers.clips.columns.added_at'))
                            ->icon(LucideIcon::Calendar)
                            ->translateLabel()
                            ->dateTime()
                            ->color('gray'),
                    ])
                        ->space(1),
                ])->from('lg'),
            ])
            ->filters([
                SelectFilter::make('broadcaster')
                    ->relationship('owner', 'name', fn (Builder $query) => $query->whereIn('id',
                        $this->getOwnerRecord()->clips->pluck('broadcaster_id')))
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->label('admin/resources/compilations.relation_managers.clips.filters.broadcaster')
                    ->translateLabel(),
                SelectFilter::make('creator')
                    ->relationship('creator', 'name', fn (Builder $query) => $query->whereIn('id',
                        $this->getOwnerRecord()->clips->pluck('creator_id')))
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->label('admin/resources/compilations.relation_managers.clips.filters.creator')
                    ->translateLabel(),
                SelectFilter::make('submitter')
                    ->relationship('submitter', 'name', fn (Builder $query) => $query->whereIn('id',
                        $this->getOwnerRecord()->clips->pluck('submitter_id')))
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->label('admin/resources/compilations.relation_managers.clips.filters.submitter')
                    ->translateLabel(),
                SelectFilter::make('claimer')
                    ->label('admin/resources/compilations.relation_managers.clips.filters.claimer')
                    ->translateLabel()
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->options(fn () => User::query()
                        ->whereIn('id', $this->getOwnerRecord()->clips()->newPivotStatement()
                            ->where('compilation_id', $this->getOwnerRecord()->getKey())
                            ->pluck('claimed_by'))
                        ->pluck('name', 'id')
                        ->prepend(__('admin/resources/compilations.relation_managers.clips.filters.claimer_option_none'), 'null'))
                    ->query(function (Builder $query, array $data): void {
                        $values = $data['values'] ?? [];
                        if (empty($values)) {
                            return;
                        }

                        $query->where(function (Builder $query) use ($values): void {
                            $ids = array_diff($values, ['null']);

                            if (in_array('null', $values, true)) {
                                $query->whereNull('clip_compilation.claimed_by');
                            }

                            if ($ids !== []) {
                                $query->orWhereIn('clip_compilation.claimed_by', $ids);
                            }
                        });
                    }),
                SelectFilter::make('adder')
                    ->label('admin/resources/compilations.relation_managers.clips.filters.adder')
                    ->translateLabel()
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->options(fn () => User::query()
                        ->whereIn('id', $this->getOwnerRecord()->clips()->newPivotStatement()
                            ->where('compilation_id', $this->getOwnerRecord()->getKey())
                            ->pluck('added_by'))
                        ->pluck('name', 'id')
                        ->prepend(__('admin/resources/compilations.relation_managers.clips.filters.adder_option_none'), 'null'))
                    ->query(function (Builder $query, array $data): void {
                        $values = $data['values'] ?? [];
                        if (empty($values)) {
                            return;
                        }

                        $query->where(function (Builder $query) use ($values): void {
                            $ids = array_diff($values, ['null']);

                            if (in_array('null', $values, true)) {
                                $query->whereNull('clip_compilation.added_by');
                            }

                            if ($ids !== []) {
                                $query->orWhereIn('clip_compilation.added_by', $ids);
                            }
                        });
                    }),
                SelectFilter::make('category')
                    ->relationship('category', 'title',
                        fn (Builder $query) => $query->whereIn('id', $this->getOwnerRecord()->clips()->pluck('category_id')))
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->label('admin/resources/compilations.relation_managers.clips.filters.category')
                    ->translateLabel(),

                TernaryFilter::make('was_removed')
                    ->label('admin/resources/compilations.relation_managers.clips.filters.was_removed.label')
                    ->translateLabel()
                    ->nullable()
                    ->placeholder(__('admin/resources/compilations.relation_managers.clips.filters.was_removed.placeholder'))
                    ->trueLabel(__('admin/resources/compilations.relation_managers.clips.filters.was_removed.true'))
                    ->falseLabel(__('admin/resources/compilations.relation_managers.clips.filters.was_removed.false'))
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('removed_at'),
                        false: fn (Builder $query) => $query->whereNull('removed_at'),
                        blank: fn (Builder $query): Builder => $query,
                    ),

                SelectFilter::make('clip_compilation.claim_status')
                    ->label('admin/resources/compilations.relation_managers.clips.filters.cutter_status')
                    ->translateLabel()
                    ->multiple()
                    ->options(CompilationClipClaimStatus::class),

                SelectFilter::make('clips.status')
                    ->label('admin/resources/compilations.relation_managers.clips.filters.clip_status')
                    ->translateLabel()
                    ->multiple()
                    ->options(ClipStatus::class),
            ])
            ->filtersFormColumns(2)
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('claim_status')
                            ->label('admin/resources/compilations.relation_managers.clips.columns.status')
                            ->translateLabel()
                            ->options(CompilationClipClaimStatus::class)
                            ->default(CompilationClipClaimStatus::Pending)
                            ->required(),
                    ])
                    ->mutateDataUsing(function (array $data): array {
                        $data['added_by'] = auth()->id();

                        return $data;
                    }),
            ])
            ->recordActions([
                CommentsAction::make()
                    ->mentionables(fn (Model $record) => User::query()->whereHas('roles')->get())
                    ->hidden(fn (): bool => ! auth()->user()->can(Permission::ViewAnyComment))
                    ->perPage(4)
                    ->loadMoreIncrementsBy(8)
                    ->modalWidth(Width::SevenExtraLarge),
                ActionGroup::make([
                    Action::make('claim')
                        ->label('admin/resources/compilations.relation_managers.clips.actions.claim')
                        ->translateLabel()
                        ->icon(LucideIcon::Lock)
                        ->rateLimit(5)
                        ->hidden(fn (Clip $record): bool => $record->pivot->claimed_by === auth()->id())
                        ->requiresConfirmation(fn (Clip $record): bool => ! is_null($record->pivot->claimed_by))
                        ->modalHeading(fn (Clip $record): string|array|null => $record->pivot->claimed_by
                            ? __('admin/resources/compilations.relation_managers.clips.actions.claim_override.heading')
                            : null)
                        ->modalDescription(fn (Clip $record): string|array|null => $record->pivot->claimed_by
                            ? __('admin/resources/compilations.relation_managers.clips.actions.claim_override.description')
                            : null)
                        ->action(function (Clip $clip): true {
                            $clip->pivot->update([
                                'claimed_by' => auth()->id(),
                            ]);

                            CompilationClipClaimed::dispatch($this->getOwnerRecord(), auth()->user(), $clip);

                            Notification::make()
                                ->title(__('admin/resources/compilations.relation_managers.clips.notifications.claimed.title'))
                                ->success()
                                ->body(__('admin/resources/compilations.relation_managers.clips.notifications.claimed.body'))
                                ->send();

                            return true;
                        }),

                    Action::make('status')
                        ->label('admin/resources/compilations.relation_managers.clips.actions.status.title')
                        ->translateLabel()
                        ->icon(LucideIcon::Clipboard)
                        ->hidden(fn (Clip $record): bool => $record->pivot->claimed_by !== auth()->id())
                        ->fillForm(fn (Clip $record): array => [
                            'status' => $record->pivot->claim_status,
                        ])
                        ->schema([
                            Select::make('status')
                                ->hiddenLabel()
                                ->options(CompilationClipClaimStatus::class)
                                ->default(CompilationClipClaimStatus::Pending)
                                ->required(),
                        ])
                        ->action(function (Clip $clip, array $data): void {
                            $oldStatus = $clip->pivot->claim_status;

                            $clip->pivot->update([
                                'claim_status' => $data['status'],
                            ]);

                            CompilationClipStatusUpdated::dispatch($this->getOwnerRecord(), auth()->user(), $clip, $oldStatus, $data['status']);

                            Notification::make()
                                ->title(__('admin/resources/compilations.relation_managers.clips.notifications.status_updated'))
                                ->success()
                                ->send();
                        }),

                    Action::make('download')
                        ->hidden() // not required? only hide it for now
                        ->label('admin/resources/clips.actions.download')
                        ->translateLabel()
                        ->icon(LucideIcon::Download)
                        ->disabled(fn (Clip $record): bool => $record->pivot->claimed_by !== auth()->id())
                        ->action(function (Clip $clip, TwitchService $twitchService, Component $livewire): bool {
                            $broadCaster = $clip->broadcaster;

                            if (! $broadCaster || empty($broadCaster->twitch_refresh_token) || $broadCaster->clip_permission === false) {
                                Notification::make()
                                    ->title(__('admin/resources/compilations.relation_managers.clips.notifications.download_error_title'))
                                    ->body(__('admin/resources/compilations.relation_managers.clips.notifications.download_error_broadcaster'))
                                    ->danger()
                                    ->send();

                                return false;
                            }

                            try {
                                $response = $twitchService->asUser($clip->broadcaster)->get(TwitchEndpoints::GetClipsDownload,
                                    [
                                        'editor_id' => $clip->broadcaster_id,
                                        'broadcaster_id' => $clip->broadcaster_id,
                                        'clip_id' => $clip->twitch_id,
                                    ]);
                                /** @var ClipDownloadDto $download */
                                $download = array_first($response);

                                if (! $download) {
                                    Notification::make()
                                        ->title(__('admin/resources/compilations.relation_managers.clips.notifications.download_error_title'))
                                        ->body(__('admin/resources/compilations.relation_managers.clips.notifications.download_error_not_found'))
                                        ->danger()
                                        ->send();

                                    return false;
                                }

                                // thanks to cors we are very limited, user still has to download it manually
                                // alternative would be downloading it to server and serving it as a proxy
                                // that way we may have a permanent copy and cache, but also more traffic
                                $livewire->js("window.open('{$download->landscape_download_url}', '_blank')");
                            } catch (TwitchApiException $e) {
                                Notification::make()
                                    ->title(__('admin/resources/compilations.relation_managers.clips.notifications.download_error_title'))
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();

                                return false;
                            }

                            return true;
                        }),
                    Action::make('copy_cutter_optimized_name')
                        ->label('admin/resources/compilations.relation_managers.clips.actions.copy_filename')
                        ->translateLabel()
                        ->icon('heroicon-o-clipboard-document-list')
                        ->color('gray')
                        ->tooltip(__('admin/resources/compilations.relation_managers.clips.actions.copy_filename_tooltip'))
                        ->action(function (Clip $clip, $livewire): void {
                            $title = Str::limit($clip->title, 50, '');

                            if (! $clip->owner) {
                                Notification::make()
                                    ->title(__('admin/resources/compilations.relation_managers.clips.notifications.filename_copy_failed_title'))
                                    ->body(__('admin/resources/compilations.relation_managers.clips.notifications.filename_copy_failed_no_broadcaster'))
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $filename = "[{$clip->id}] {$clip->owner->name} - {$clip->category->title} - {$title}.mp4";
                            $livewire->js("window.navigator.clipboard.writeText('{$filename}');");

                            Notification::make()
                                ->title(__('admin/resources/compilations.relation_managers.clips.notifications.filename_copied'))
                                ->body($filename)
                                ->success()
                                ->send();
                        }),
                    Action::make('unclaim')
                        ->label('admin/resources/compilations.relation_managers.clips.actions.unclaim')
                        ->translateLabel()
                        ->color('warning')
                        ->icon(LucideIcon::LockOpen)
                        ->hidden(fn (Clip $record): bool => $record->pivot->claimed_by !== auth()->id())
                        ->requiresConfirmation()
                        ->action(function (Clip $clip): void {
                            if ($clip->pivot->claimed_by !== auth()->id()) {
                                Notification::make()
                                    ->title(__('admin/resources/compilations.relation_managers.clips.actions.unclaim_failed.title'))
                                    ->body(__('admin/resources/compilations.relation_managers.clips.actions.unclaim.message'))
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $clip->pivot->update([
                                'claimed_by' => null,
                            ]);

                            CompilationClipUnclaimed::dispatch($this->getOwnerRecord(), auth()->user(), $clip);

                            Notification::make()
                                ->title(__('admin/resources/compilations.relation_managers.clips.notifications.unclaimed_title'))
                                ->success()
                                ->send();
                        }),
                    DetachAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ])
            ->paginated(false)
            ->openRecordUrlInNewTab();
    }
}
