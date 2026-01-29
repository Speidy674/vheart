<?php

declare(strict_types=1);

namespace App\Filament\Resources\Reports\Schemas;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Illuminate\Database\Eloquent\Model;

class ReportInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->columnSpanFull()
                    ->schema([
                        Group::make([
                            Section::make('Report Overview')
                                ->icon('heroicon-m-document-text')
                                ->schema([
                                    Grid::make(2)->schema([
                                        TextEntry::make('reason')
                                            ->label('Reason')
                                            ->badge()
                                            ->size(TextSize::Large)
                                            ->color('danger'),

                                        TextEntry::make('status')
                                            ->label('Current Status')
                                            ->badge()
                                            ->size(TextSize::Large)
                                            ->alignEnd(),
                                    ]),

                                    TextEntry::make('description')
                                        ->prose()
                                        ->markdown()
                                        ->label('Description')
                                        ->placeholder('No specific description provided.')
                                        ->columnSpanFull()
                                        ->extraAttributes(['class' => 'leading-relaxed']),
                                ]),

                            Section::make('Reported Content')
                                ->headerActions([
                                    Action::make('view')
                                        ->color('info')
                                        ->icon('heroicon-m-arrow-top-right-on-square')
                                        ->url(fn (Model $record) => Filament::getResourceUrl($record->reportable, 'view'))
                                        ->openUrlInNewTab(),
                                ])
                                ->icon('heroicon-m-eye')
                                ->columns(2)
                                ->schema([
                                    TextEntry::make('reportable_type')
                                        ->label('Content Type')
                                        ->badge()
                                        ->color('gray'),

                                    TextEntry::make('reportable_id')
                                        ->label('Target ID')
                                        ->fontFamily('mono')
                                        ->copyable(),
                                ]),
                        ])->columnSpan(['lg' => 2]),

                        Group::make([
                            Section::make('Involved Parties')
                                ->icon('heroicon-m-users')
                                ->schema([
                                    TextEntry::make('reporter.name')
                                        ->url(fn (Model $record) => $record->reporter ? Filament::getResourceUrl($record->reporter, 'view') : null, true)
                                        ->label('Reporter')
                                        ->icon('heroicon-m-user')
                                        ->weight(FontWeight::Bold)
                                        ->color('gray'),

                                    TextEntry::make('claimer.name')
                                        ->url(fn (Model $record) => $record->claimer ? Filament::getResourceUrl($record->claimer, 'view') : null, true)
                                        ->label('Claimed By')
                                        ->icon('heroicon-m-shield-check')
                                        ->placeholder('Unclaimed'),

                                    TextEntry::make('resolver.name')
                                        ->url(fn (Model $record) => $record->resolver ? Filament::getResourceUrl($record->resolver, 'view') : null, true)
                                        ->label('Resolved By')
                                        ->icon('heroicon-m-check-badge')
                                        ->placeholder('Unresolved'),
                                ]),

                            Section::make('Timeline')
                                ->icon('heroicon-m-clock')
                                ->compact()
                                ->schema([
                                    TextEntry::make('created_at')
                                        ->label('Submitted At')
                                        ->dateTime(),

                                    TextEntry::make('claimed_at')
                                        ->label('Claimed')
                                        ->dateTime()
                                        ->placeholder('-'),

                                    TextEntry::make('resolved_at')
                                        ->label('Resolved')
                                        ->dateTime()
                                        ->placeholder('-')
                                        ->color(fn ($state) => $state ? 'success' : 'gray'),

                                    TextEntry::make('updated_at')
                                        ->label('Last Activity')
                                        ->since()
                                        ->color('gray'),
                                ]),
                        ])->columnSpan(['lg' => 1]),
                    ]),
            ]);
    }
}
