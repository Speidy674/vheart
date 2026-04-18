<?php

declare(strict_types=1);

namespace App\Filament\Resources\Clips;

use App\Enums\Filament\LucideIcon;
use App\Filament\Actions\ReportAction;
use App\Models\Clip;
use Filament\Actions\ActionGroup;

class ClipActions
{
    public static function reportableActionGroup(): ActionGroup
    {
        return ActionGroup::make([
            ReportAction::make(),
            ReportAction::make('report_submitter')
                ->hidden(fn (Clip $record) => ! $record->submitter || $record->submitter_id === 0 || $record->submitter_id === auth()->id())
                ->reportable(fn (Clip $record) => $record->submitter)
                ->reportableAlias('Submitter'),
            ReportAction::make('report_clipper')
                ->hidden(fn (Clip $record) => ! $record->creator || $record->creator_id === auth()->id())
                ->reportable(fn (Clip $record) => $record->creator)
                ->reportableAlias('Clipper'),
        ])
            ->label('Report')
            ->color('danger')
            ->icon(LucideIcon::Flag);
    }
}
