<?php

declare(strict_types=1);

namespace App\Filament\Resources\Broadcasters\RelationManagers;

use App\Enums\Broadcaster\BroadcasterConsent;
use App\Filament\Resources\Broadcasters\Pages\EditBroadcaster;
use App\Models\Broadcaster\BroadcasterConsentLog;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontFamily;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ConsentLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'consentLogs';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass !== EditBroadcaster::class;
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema;
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('changed_at', 'desc')
            ->recordTitleAttribute('changed_at')
            ->deferLoading()
            ->columns([
                TextColumn::make('changed_at')
                    ->label('Changed At')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('changedBy.name')
                    ->label('Changed By'),

                TextColumn::make('state')
                    ->formatStateUsing(fn (BroadcasterConsent $state) => $state->getLabel())
                    ->label('Consents')
                    ->color('success')
                    ->separator()
                    ->badge(),

                TextColumn::make('change_reason')
                    ->placeholder('No Reason')
                    ->label('Reason')
                    ->wrap(),

                IconColumn::make('checksum_validity')
                    ->tooltip(fn (BroadcasterConsentLog $record) => $record->isValid()
                        ? 'Checksum valid'
                        : 'Checksum invalid, record may have been tampered with'
                    )
                    ->state(fn (BroadcasterConsentLog $record) => $record->isValid())
                    ->label('Status')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter()
                    ->boolean(),

                TextColumn::make('checksum')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->fontFamily(FontFamily::Mono)
                    ->label('Checksum')
                    ->color('gray'),
            ]);
    }
}
