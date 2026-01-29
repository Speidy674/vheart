<?php

declare(strict_types=1);

namespace App\Filament\Resources\Reports\Pages;

use App\Enums\Reports\ReportStatus;
use App\Filament\Resources\Reports\ReportResource;
use App\Models\Report;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewReport extends ViewRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('claim')
                ->label('Claim')
                ->icon(Heroicon::LockClosed)
                ->color('info')
                ->visible(fn (Report $record) => $record->claimed_by === null && $record->status === ReportStatus::Pending)
                ->action(function (Report $record) {
                    $record->update([
                        'claimed_by' => auth()->id(),
                        'claimed_at' => now(),
                    ]);
                }),
            Action::make('resolve')
                ->label('Resolve')
                ->icon('heroicon-o-check')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (Report $record) => $record->claimed_by === auth()->id() && $record->status === ReportStatus::Pending)
                ->action(function (Report $record) {
                    $record->update([
                        'status' => ReportStatus::Resolved,
                        'resolved_by' => auth()->id(),
                        'resolved_at' => now(),
                        'deleted_at' => now(),
                    ]);
                }),
        ];
    }
}
