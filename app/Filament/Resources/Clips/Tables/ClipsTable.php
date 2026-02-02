<?php

declare(strict_types=1);

namespace App\Filament\Resources\Clips\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontFamily;
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
            ]))
            ->columns([
                Split::make([
                    Stack::make([
                        ImageColumn::make('thumbnail_url')
                            ->label('admin/resources/clips.table.columns.thumbnail')
                            ->translateLabel()
                            ->imageHeight(200)
                            ->alignCenter()
                            ->extraImgAttributes(['class' => 'object-cover rounded aspect-video']),
                    ])->grow(false),

                    Stack::make([
                        TextColumn::make('title')
                            ->label('admin/resources/clips.table.columns.title')
                            ->translateLabel()
                            ->weight('bold')
                            ->searchable()
                            ->wrap(),

                        TextColumn::make('game.title')
                            ->label('admin/resources/clips.table.columns.category')
                            ->translateLabel()
                            ->icon(Heroicon::Tag)
                            ->color('primary')
                            ->searchable(),

                        TextColumn::make('duration')
                            ->label('admin/resources/clips.table.columns.duration')
                            ->translateLabel()
                            ->icon(Heroicon::Clock)
                            ->sortable()
                            ->formatStateUsing(fn (int $state) => gmdate('i:s', $state))
                            ->fontFamily(FontFamily::Mono)
                            ->color('gray'),
                    ]),

                    Stack::make([
                        TextColumn::make('date')
                            ->tooltip(__('admin/resources/clips.table.columns.created_at'))
                            ->icon(Heroicon::Calendar)
                            ->dateTime()
                            ->color('gray'),
                        TextColumn::make('created_at')
                            ->tooltip(__('admin/resources/clips.table.columns.submitted_at'))
                            ->icon(Heroicon::Calendar)
                            ->dateTime()
                            ->color('gray'),
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
                    ->label('admin/resources/clips.filters.clipper')
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
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ]),
            ]);
    }
}
