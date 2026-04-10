<?php

declare(strict_types=1);

namespace App\Filament\AdminPanel\Resources\Broadcasters\Pages;

use App\Enums\Broadcaster\BroadcasterConsent;
use App\Filament\AdminPanel\Resources\Broadcasters\BroadcasterResource;
use App\Models\Broadcaster\Broadcaster;
use App\Models\Broadcaster\BroadcasterConsentLog;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use JsonException;

class EditBroadcaster extends EditRecord
{
    protected static string $resource = BroadcasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    /**
     * @throws JsonException
     */
    protected function afterSave(): void
    {
        /** @var Broadcaster $broadcaster */
        $broadcaster = $this->record;

        $newState = $broadcaster->consent->values()
            ->map(fn (BroadcasterConsent $e) => $e->value)
            ->all();

        $alreadyLogged = $broadcaster->consentLogs()
            ->where('state', json_encode($newState, JSON_THROW_ON_ERROR))
            ->latest('changed_at')
            ->exists();

        if ($alreadyLogged) {
            return;
        }

        BroadcasterConsentLog::create([
            'broadcaster_id' => $broadcaster->id,
            'state' => $newState,
            'changed_by' => auth()->id(),
            'changed_at' => now(),
        ]);
    }
}
