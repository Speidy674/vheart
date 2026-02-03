<?php

declare(strict_types=1);

namespace App\Filament\Resources\Clips\Tables;

use App\Enums\ClipVoteType;
use App\Models\Clip;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with([
                'game',
                'broadcaster',
                'creator',
                'submitter',
            ])->withCount([
                'votes as votes_jury' => function (Builder $query) {
                    $query->where('type', ClipVoteType::Jury)->whereVoted(true);
                },
                'votes as votes_public' => function (Builder $query) {
                    $query->where('type', ClipVoteType::Public)->whereVoted(true);
                },
            ]))
            ->columns([
                Split::make([
                    Stack::make([
                        ImageColumn::make('thumbnail_url')
                            ->label('admin/resources/clips.table.columns.thumbnail')
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
                            ->label('admin/resources/clips.table.columns.title')
                            ->translateLabel()
                            ->weight('bold')
                            ->searchable()
                            ->wrap(),

                        Split::make([
                            TextColumn::make('duration')
                                ->label(__('admin/resources/clips.table.columns.duration'))
                                ->tooltip(__('admin/resources/clips.table.columns.duration'))
                                ->icon(Heroicon::Clock)
                                ->size(TextSize::Medium)
                                ->sortable()
                                ->formatStateUsing(fn (int $state) => gmdate('i:s', $state))
                                ->fontFamily(FontFamily::Mono)
                                ->badge()
                                ->color('gray'),

                            TextColumn::make('votes_jury')
                                ->tooltip(__('admin/resources/clips.table.columns.votes_jury'))
                                ->label(__('admin/resources/clips.table.columns.votes_jury'))
                                ->icon(Heroicon::Star)
                                ->size(TextSize::Medium)
                                ->sortable()
                                ->badge()
                                ->color('warning'),
                            TextColumn::make('votes_public')
                                ->label(__('admin/resources/clips.table.columns.votes_public'))
                                ->tooltip(__('admin/resources/clips.table.columns.votes_public'))
                                ->size(TextSize::Medium)
                                ->icon(Heroicon::UserGroup)
                                ->sortable()
                                ->badge()
                                ->color('success'),
                        ])->grow(false),
                    ])->space(),

                    Stack::make([
                        TextColumn::make('broadcaster.name')
                            ->tooltip(__('admin/resources/clips.table.columns.broadcaster'))
                            ->icon(Heroicon::VideoCamera)
                            ->color('gray'),

                        TextColumn::make('creator.name')
                            ->tooltip(__('admin/resources/clips.table.columns.creator'))
                            ->icon(Heroicon::Scissors)
                            ->color('gray'),

                        TextColumn::make('submitter.name')
                            ->tooltip(__('admin/resources/clips.table.columns.submitter'))
                            ->icon(Heroicon::User)
                            ->color('gray'),
                    ])
                        ->space(1),

                    Stack::make([
                        TextColumn::make('date')
                            ->label(__('admin/resources/clips.table.columns.created_at'))
                            ->tooltip(__('admin/resources/clips.table.columns.created_at'))
                            ->icon(Heroicon::Calendar)
                            ->dateTime()
                            ->sortable()
                            ->color('gray'),
                        TextColumn::make('created_at')
                            ->label(__('admin/resources/clips.table.columns.submitted_at'))
                            ->tooltip(__('admin/resources/clips.table.columns.submitted_at'))
                            ->icon(Heroicon::Calendar)
                            ->dateTime()
                            ->sortable()
                            ->color('gray'),

                        Split::make([
                            ImageColumn::make('game.box_art')
                                ->imageHeight(40)
                                ->alignCenter()
                                ->getStateUsing(function (Clip $record) {
                                    return $record->game?->getBoxArt();
                                })
                                ->extraImgAttributes([
                                    'class' => 'object-cover rounded-md aspect-[3/4]',
                                ])
                                ->grow(false),
                            TextColumn::make('game.title')
                                ->label('admin/resources/clips.table.columns.category')
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
                SelectFilter::make('broadcaster')
                    ->relationship('broadcaster', 'name', function (Builder $query): Builder {
                        return $query->whereHas('broadcastedClips');
                    })
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->label('admin/resources/clips.filters.broadcaster')
                    ->translateLabel(),
                SelectFilter::make('creator')
                    ->relationship('creator', 'name', function (Builder $query): Builder {
                        return $query->whereHas('createdClips');
                    })
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->label('admin/resources/clips.filters.creator')
                    ->translateLabel(),
                SelectFilter::make('submitter')
                    ->relationship('submitter', 'name', function (Builder $query): Builder {
                        return $query->whereHas('submittedClips');
                    })
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->label('admin/resources/clips.filters.submitter')
                    ->translateLabel(),
                SelectFilter::make('game')
                    ->relationship('game', 'title')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->label('admin/resources/clips.filters.game')
                    ->translateLabel(),
                SelectFilter::make('tags')
                    ->relationship('tags', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->label('admin/resources/clips.filters.tags')
                    ->translateLabel(),
            ])
            ->defaultSort('votes_public', 'desc')
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ]),
            ]);
    }
}
