<?php

declare(strict_types=1);

namespace App\Filament\Resources\Reports\Pages;

use App\Enums\Permission;
use App\Enums\Reports\ReportStatus;
use App\Enums\Reports\ResolveAction;
use App\Filament\Resources\Reports\ReportResource;
use App\Models\Report;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Kirschbaum\Commentions\Filament\Actions\CommentsAction;

class ViewReport extends ViewRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CommentsAction::make()
                ->mentionables(fn (Model $record) => User::query()->whereHas('roles')->get())
                ->hidden(fn () => ! auth()->user()->can(Permission::ViewAnyComment))
                ->perPage(4)
                ->loadMoreIncrementsBy(8)
                ->modalWidth(Width::SevenExtraLarge),
            Action::make('claim')
                ->requiresConfirmation()
                ->label('Claim')
                ->icon(Heroicon::LockClosed)
                ->color('info')
                ->visible(fn (Report $record) => $record->claimed_by === null && $record->status === ReportStatus::Pending)
                ->action(function (Report $record) {
                    $record->update([
                        'status' => ReportStatus::InReview,
                        'claimed_by' => auth()->id(),
                        'claimed_at' => now(),
                    ]);
                }),
            Action::make('unclaim')
                ->requiresConfirmation()
                ->label('Unclaim')
                ->icon(Heroicon::LockClosed)
                ->color('info')
                ->visible(fn (Report $record) => $record->claimed_by !== null && $record->status === ReportStatus::InReview)
                ->action(function (Report $record) {
                    $record->update([
                        'status' => ReportStatus::Pending,
                        'claimed_by' => null,
                        'claimed_at' => null,
                    ]);
                }),
            Action::make('resolve')
                ->requiresConfirmation()
                ->label('Resolve')
                ->icon('heroicon-o-check')
                ->color('success')
                ->modalWidth(Width::ThreeExtraLarge)
                ->schema([
                    Select::make('action')
                        ->required()
                        ->reactive()
                        ->options(ResolveAction::class),
                    MarkdownEditor::make('reason')
                        ->minLength(10)
                        ->hint('Required if Action is Other')
                        ->required(fn (Get $get): bool => $get('action') !== null && $get('action') === ResolveAction::Other),
                ])
                ->visible(fn (Report $record) => $record->claimed_by === auth()->id() && $record->status === ReportStatus::InReview)
                ->action(function (Report $record, array $data) {
                    $record->update([
                        'status' => ReportStatus::Resolved,
                        'resolve_action' => $data['action'],
                        'resolve_description' => $data['reason'],
                        'resolved_by' => auth()->id(),
                        'resolved_at' => now(),
                        'deleted_at' => now(),
                    ]);
                }),
        ];
    }
}
