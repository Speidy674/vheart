<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Clips\RelationManagers;

use App\Enums\Clips\CompilationStatus;
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
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScope(filament()->getTenancyScopeName())->whereIn('compilations.status', CompilationStatus::getVoteDisabledCases()))
            ->columns([
                TextColumn::make('title')
                    ->label('admin/resources/clips.table.columns.title')
                    ->translateLabel()
                    ->wrap()
                    ->searchable(),

                TextColumn::make('type')
                    ->label('admin/resources/compilations.form.type')
                    ->translateLabel()
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('admin/resources/clips.table.columns.created_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
