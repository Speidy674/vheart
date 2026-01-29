<?php

namespace App\Filament\Resources\Reports\Tables;

use App\Enums\Reports\ReportReason;
use App\Enums\Reports\ReportStatus;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('reason')
                    ->wrap()
                    ->limit(50)
                    ->sortable(),

                TextColumn::make('reportable')
                    ->formatStateUsing(fn (Model $record) => $record->reportable->{$record->reportable->getReportableTitleAttribute()})
                    ->url(fn (Model $record) => Filament::getResourceUrl($record->reportable, 'view'))
                    ->badge()
                    ->openUrlInNewTab(),

                TextColumn::make('reporter.name')
                    ->label('Reported By')
                    ->searchable(),

                TextColumn::make('claimer.name')
                    ->label('Claimed By')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->options(ReportStatus::class),

                SelectFilter::make('reason')
                    ->options(ReportReason::class),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([]);
    }
}
