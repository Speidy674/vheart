<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Clips\Schemas;

use App\Enums\Clips\ClipStatus;
use App\Filament\Infolists\Components\TwitchEmbedEntry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TwitchEmbedEntry::make('twitch_id')
                            ->hiddenLabel()
                            ->alignCenter(),
                    ])->columnSpan(2),
                Section::make()
                    ->compact()
                    ->schema([
                        TextInput::make('title')
                            ->label('dashboard/resources/clips.form.title')
                            ->translateLabel()
                            ->required(),
                        Select::make('category_id')
                            ->label('dashboard/resources/clips.form.category')
                            ->translateLabel()
                            ->required()
                            ->preload()
                            ->relationship('category', 'title')
                            ->searchable(),
                        Select::make('tags')
                            ->label('dashboard/resources/clips.form.tags')
                            ->translateLabel()
                            ->multiple()
                            ->required()
                            ->minItems(1)
                            ->maxItems(3)
                            ->preload()
                            ->relationship('tags', 'name')
                            ->searchable(),
                        Select::make('status')
                            ->label('dashboard/resources/clips.form.status')
                            ->translateLabel()
                            ->required()
                            ->options(ClipStatus::class),
                        TextEntry::make('duration')
                            ->label('dashboard/resources/clips.form.duration')
                            ->translateLabel()
                            ->formatStateUsing(function ($state): string {
                                $totalSeconds = (int) round($state);

                                $minutes = intdiv($totalSeconds, 60);
                                $seconds = $totalSeconds % 60;

                                return sprintf('%d:%02d', $minutes, $seconds);
                            }),
                        Select::make('creator')
                            ->relationship('creator', 'name')
                            ->disabled()
                            ->label('dashboard/resources/clips.form.creator')
                            ->translateLabel(),
                        Select::make('submitter')
                            ->relationship('submitter', 'name')
                            ->disabled()
                            ->label('dashboard/resources/clips.form.submitted_by')
                            ->translateLabel(),
                        DateTimePicker::make('date')
                            ->label('dashboard/resources/clips.form.created_at')
                            ->translateLabel()
                            ->disabled(),
                    ]),
            ])->columns(3);
    }
}
