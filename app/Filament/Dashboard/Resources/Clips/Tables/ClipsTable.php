<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Clips\Tables;

use App\Enums\Clips\ClipStatus;
use App\Enums\ClipVoteType;
use App\Models\Clip;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->whereBroadcastBy(auth()->user()?->id)->with([
                'category',
                'broadcaster',
                'creator',
                'submitter',
            ])->withCount([
                'votes as votes_jury' => function (Builder $query): void {
                    $query->where('type', ClipVoteType::Jury)->whereVoted(true);
                },
                'votes as votes_public' => function (Builder $query): void {
                    $query->where('type', ClipVoteType::Public)->whereVoted(true);
                },
            ]))
            ->columns([
                Split::make([
                    Stack::make([
                        ImageColumn::make('thumbnail_url')
                            ->label('dashboard/resources/clips.table.columns.thumbnail')
                            ->translateLabel()
                            ->imageHeight(100)
                            ->alignCenter()
                            ->extraImgAttributes([
                                'class' => 'object-cover rounded aspect-video',
                                'loading' => 'lazy',
                            ]),
                    ])->grow(false),

                    Stack::make([
                        TextColumn::make('title')
                            ->label('dashboard/resources/clips.table.columns.title')
                            ->translateLabel()
                            ->weight('bold')
                            ->searchable()
                            ->wrap(),

                        Split::make([
                            TextColumn::make('duration')
                                ->label(__('dashboard/resources/clips.table.columns.duration'))
                                ->tooltip(__('dashboard/resources/clips.table.columns.duration'))
                                ->icon(Heroicon::Clock)
                                ->size(TextSize::Medium)
                                ->sortable()
                                ->formatStateUsing(fn (int $state): string => gmdate('i:s', $state))
                                ->fontFamily(FontFamily::Mono)
                                ->badge()
                                ->color('gray'),
                            TextColumn::make('votes_public')
                                ->label(__('dashboard/resources/clips.table.columns.votes'))
                                ->tooltip(__('dashboard/resources/clips.table.columns.votes'))
                                ->size(TextSize::Medium)
                                ->icon(Heroicon::UserGroup)
                                ->sortable()
                                ->badge()
                                ->color('success'),
                            TextColumn::make('status')
                                ->label('dashboard/resources/clips.table.columns.status')
                                ->tooltip(__('dashboard/resources/clips.table.columns.status'))
                                ->size(TextSize::Medium)
                                ->icon(Heroicon::Clipboard)
                                ->badge()
                                ->translateLabel(),
                        ])->grow(false),

                        TextColumn::make('tags.name')
                            ->color('gray')
                            ->badge(),
                    ])->space(),

                    Stack::make([
                        TextColumn::make('broadcaster.name')
                            ->tooltip(__('dashboard/resources/clips.table.columns.broadcaster'))
                            ->icon(Heroicon::VideoCamera)
                            ->color('gray'),

                        TextColumn::make('creator.name')
                            ->tooltip(__('dashboard/resources/clips.table.columns.creator'))
                            ->icon(Heroicon::Scissors)
                            ->color('gray'),

                        TextColumn::make('submitter.name')
                            ->tooltip(__('dashboard/resources/clips.table.columns.submitter'))
                            ->icon(Heroicon::User)
                            ->color('gray'),
                    ])
                        ->space(1),

                    Stack::make([
                        TextColumn::make('date')
                            ->label(__('dashboard/resources/clips.table.columns.created_at'))
                            ->tooltip(__('dashboard/resources/clips.table.columns.created_at'))
                            ->icon(Heroicon::Calendar)
                            ->dateTime()
                            ->sortable()
                            ->color('gray'),
                        TextColumn::make('created_at')
                            ->label(__('dashboard/resources/clips.table.columns.submitted_at'))
                            ->tooltip(__('dashboard/resources/clips.table.columns.submitted_at'))
                            ->icon(Heroicon::Calendar)
                            ->dateTime()
                            ->sortable()
                            ->color('gray'),

                        Split::make([
                            ImageColumn::make('category.box_art')
                                ->imageHeight(40)
                                ->alignCenter()
                                ->getStateUsing(fn (Clip $record) => $record->category?->getBoxArt())
                                ->extraImgAttributes([
                                    'class' => 'object-cover rounded-md aspect-[3/4]',
                                ])
                                ->grow(false),
                            TextColumn::make('category.title')
                                ->label('dashboard/resources/clips.table.columns.category')
                                ->translateLabel()
                                ->weight('medium')
                                ->wrap()
                                ->color('gray')
                                ->searchable(),
                        ])
                            ->grow(false),
                    ])
                        ->space(1),
                ])->from('lg'),
            ])
            ->filters([
                SelectFilter::make('creator')
                    ->relationship('creator', 'name', fn (Builder $query): Builder => $query->whereHas('createdClips'))
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->label('dashboard/resources/clips.filters.creator')
                    ->translateLabel(),
                SelectFilter::make('submitter')
                    ->relationship('submitter', 'name', fn (Builder $query): Builder => $query->whereHas('submittedClips'))
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->label('dashboard/resources/clips.filters.submitter')
                    ->translateLabel(),
                SelectFilter::make('category')
                    ->relationship('category', 'title')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->label('dashboard/resources/clips.filters.category')
                    ->translateLabel(),

                SelectFilter::make('status')
                    ->options(ClipStatus::class)
                    ->multiple()
                    ->searchable()
                    ->label('dashboard/resources/clips.filters.status')
                    ->translateLabel(),

                TernaryFilter::make('status_visibility')
                    ->label('dashboard/resources/clips.filters.status_visibility.label')
                    ->translateLabel()
                    ->placeholder(__('dashboard/resources/clips.filters.status_visibility.placeholder'))
                    ->trueLabel(__('dashboard/resources/clips.filters.status_visibility.true'))
                    ->falseLabel(__('dashboard/resources/clips.filters.status_visibility.false'))
                    ->queries(
                        true: fn (Builder $query) => $query->whereIn('status', [ClipStatus::Blocked, ClipStatus::NeedApproval]),
                        blank: fn (Builder $query): Builder => $query,
                        false: fn (Builder $query) => $query->whereNotIn('status', [ClipStatus::Blocked, ClipStatus::NeedApproval]),
                    ),

                SelectFilter::make('tags')
                    ->relationship('tags', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->label('dashboard/resources/clips.filters.tags')
                    ->translateLabel(),

                self::makeDateRangeFilter('created', 'date'),
                self::makeDateRangeFilter('submission', 'created_at'),
            ], layout: FiltersLayout::Modal)
            ->filtersFormColumns(3)
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ]),
            ]);
    }

    // These filters get out of hand
    /**
     * Returns a From-To Date filter with presets
     */
    private static function makeDateRangeFilter(string $name, string $column): Filter
    {
        $translationPrefix = 'dashboard/resources/clips.filters.'.$name.'_range.';
        $translationPrefixPresets = 'dashboard/resources/clips.filters.date_range_presets.';

        return Filter::make($name.'_range')
            ->schema([
                Fieldset::make($translationPrefix.'label')
                    ->translateLabel()
                    ->columnSpanFull()
                    ->schema([
                        Select::make('presets')
                            ->label($translationPrefixPresets.'label')
                            ->translateLabel()
                            ->dehydrated(false)
                            ->options([
                                'today' => __($translationPrefixPresets.'options.today'),
                                'last_7_days' => __($translationPrefixPresets.'options.last_7_days'),
                                'last_30_days' => __($translationPrefixPresets.'options.last_30_days'),
                                'last_90_days' => __($translationPrefixPresets.'options.last_90_days'),
                                'this_month' => __($translationPrefixPresets.'options.this_month'),
                                'last_month' => __($translationPrefixPresets.'options.last_month'),
                            ])
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set): void {
                                if (! $state) {
                                    return;
                                }

                                $from = match ($state) {
                                    'today' => now()->startOfDay(),
                                    'last_7_days' => now()->subDays(6)->startOfDay(),
                                    'last_30_days' => now()->subDays(29)->startOfDay(),
                                    'last_90_days' => now()->subDays(89)->startOfDay(),
                                    'this_month' => now()->startOfMonth(),
                                    'last_month' => now()->subMonth()->startOfMonth(),
                                };

                                $to = match ($state) {
                                    'last_month' => now()->subMonth()->endOfMonth(),
                                    default => null,
                                };

                                $set('from', $from?->toDateString());
                                $set('to', $to?->toDateString());
                            })
                            ->columnSpanFull(),
                        DatePicker::make('from')
                            ->label($translationPrefix.'form.from')
                            ->translateLabel()
                            ->suffixAction(
                                Action::make($name.'clear_from')
                                    ->label($translationPrefix.'actions.clear_from')
                                    ->translateLabel()
                                    ->iconButton()
                                    ->icon(Heroicon::XMark)
                                    ->color('gray')
                                    ->action(function ($set): void {
                                        $set('from', null);
                                    }),
                            ),
                        DatePicker::make('to')
                            ->label($translationPrefix.'form.to')
                            ->translateLabel()
                            ->suffixAction(
                                Action::make($name.'clear_to')
                                    ->label($translationPrefix.'actions.clear_to')
                                    ->translateLabel()
                                    ->iconButton()
                                    ->icon(Heroicon::XMark)
                                    ->color('gray')
                                    ->action(function ($set): void {
                                        $set('to', null);
                                    }),
                            ),
                    ])
                    ->columns(2),
            ])
            ->columns(2)
            ->columnSpanFull()
            ->query(fn (Builder $query, array $data): Builder => $query
                ->when(
                    $data['from'],
                    fn (Builder $query, $date): Builder => $query->whereDate($column, '>=', $date),
                )
                ->when(
                    $data['to'],
                    fn (Builder $query, $date): Builder => $query->whereDate($column, '<=', $date),
                ))
            ->indicateUsing(function (array $data) use ($name, $translationPrefix): array {
                $indicators = [];
                if ($data['from'] ?? null) {
                    $indicators[$name.'_range_from'] = __($translationPrefix.'indicators.from', ['value' => Carbon::parse($data['from'])->toFormattedDateString()]);
                }
                if ($data['to'] ?? null) {
                    $indicators[$name.'_range_to'] = __($translationPrefix.'indicators.to', ['value' => Carbon::parse($data['to'])->toFormattedDateString()]);
                }

                return $indicators;
            });
    }
}
