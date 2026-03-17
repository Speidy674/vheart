<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Clips\RelationManagers;

use App\Enums\Clips\CompilationStatus;
use App\Models\Clip\Compilation;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CompilationsRelationManager extends RelationManager
{
    protected static string $relationship = 'compilations';

    protected static bool $isScopedToTenant = false;

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return true;
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('compilations.status', CompilationStatus::getVoteDisabledCases()))
            ->columns([
                TextColumn::make('title')
                    ->label('dashboard/resources/compilations.table.columns.title')
                    ->translateLabel()
                    ->wrap()
                    ->searchable(),
                TextColumn::make('type')
                    ->label('dashboard/resources/compilations.table.columns.type')
                    ->translateLabel()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->recordActions([
                Action::make('open_youtube_url')
                    ->url(fn (Compilation $record): string => $record->youtube_url, true)
                    ->label('dashboard/resources/compilations.actions.open_youtube_url')
                    ->translateLabel()
                    ->hidden(fn (Compilation $record): bool => ! $record->youtube_url),
            ]);
    }
}
