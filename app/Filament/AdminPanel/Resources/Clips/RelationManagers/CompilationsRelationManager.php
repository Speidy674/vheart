<?php

declare(strict_types=1);

namespace App\Filament\AdminPanel\Resources\Clips\RelationManagers;

use App\Filament\AdminPanel\Resources\Compilations\CompilationResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompilationsRelationManager extends RelationManager
{
    protected static string $relationship = 'compilations';

    protected static ?string $relatedResource = CompilationResource::class;

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
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

                TextColumn::make('pivot.claimer.name')
                    ->label('admin/resources/compilations.relation_managers.clips.columns.claimer')
                    ->translateLabel(),

                TextColumn::make('pivot.status')
                    ->label('admin/resources/compilations.relation_managers.clips.columns.status_cutter')
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
