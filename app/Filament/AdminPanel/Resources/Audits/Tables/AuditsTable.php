<?php

declare(strict_types=1);

namespace App\Filament\AdminPanel\Resources\Audits\Tables;

use App\Enums\Filament\LucideIcon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuditsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('causer_type')
                    ->label('Actor Type')
                    ->color('gray')
                    ->badge(),

                TextColumn::make('causer_id')
                    ->label('Actor ID')
                    ->color('gray'),

                TextColumn::make('auditable_type')
                    ->label('Resource')
                    ->color('info')
                    ->badge(),

                TextColumn::make('auditable_id')
                    ->label('Resource ID')
                    ->color('gray'),

                TextColumn::make('event')
                    ->color(fn (string $state): string => match ($state) {
                        'created', 'restored' => 'success',
                        'updated' => 'warning',
                        'deleted', 'forceDeleted' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): LucideIcon => match ($state) {
                        'created' => LucideIcon::PlusCircle,
                        'updated' => LucideIcon::Pencil,
                        'deleted' => LucideIcon::Trash,
                        default => LucideIcon::CircleQuestionMark,
                    })
                    ->badge(),

                TextColumn::make('ip_address')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('IP Address')
                    ->copyMessage('IP copied')
                    ->fontFamily('mono')
                    ->searchable(),

                TextColumn::make('user_agent')
                    ->tooltip(fn (TextColumn $column): ?string => mb_strlen($column->getState()) > 40 ? $column->getState() : null)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('User Agent')
                    ->limit(40)
                    ->searchable(),

                TextColumn::make('request_id')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->fontFamily('mono')
                    ->label('Request ID')
                    ->limit(20)
                    ->searchable()
                    ->copyable(),

                TextColumn::make('created_at')
                    ->tooltip(fn ($record) => $record->created_at->format('d.m.Y H:i:s'))
                    ->dateTime('d. M Y, H:i')
                    ->label('Timestamp')
                    ->sortable()
                    ->since(),

                TextColumn::make('updated_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
