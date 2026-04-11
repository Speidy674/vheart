<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\ImportClipAction;
use App\Models\Clip;
use App\Models\Clip\Tag;
use App\Models\User;
use App\Services\Twitch\Data\ClipDto;
use App\Services\Twitch\TwitchService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

use function Laravel\Prompts\multisearch;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\text;

#[Signature('app:clip')]
#[Description('Import a Clip (Bypasses any restrictions, too lazy to implement them here too)')]
class ImportClipCommand extends Command
{
    public function handle(TwitchService $twitchService, ImportClipAction $importClipAction): int
    {
        $appService = $twitchService->asApp();

        $clipUri = $twitchService->parseClipId(text(
            label: 'Clip URI',
            validate: fn (string $value): ?string => $twitchService->parseClipId($value) ? null : 'Invalid Clip URI'
        ));

        if (Clip::where('twitch_id', $clipUri)->exists()) {
            $this->error("Clip {$clipUri} already exists.");

            return CommandAlias::FAILURE;
        }

        $clipDto = spin(
            callback: fn (): ?ClipDto => $appService->getClip($clipUri),
        );

        if (! $clipDto) {
            $this->error("Clip {$clipUri} not found.");

            return CommandAlias::FAILURE;
        }

        $this->info('Found Clip: '.$clipDto->title);

        $selectedIds = multisearch(
            label: 'Search tags (1–3)',
            options: fn (string $value) => Tag::where('name', 'ilike', "%{$value}%")
                ->orWhere('id', (int) $value)
                ->limit(10)
                ->orderBy('id', 'desc')
                ->pluck('name', 'id')
                ->all(),
            placeholder: 'Search Tags...',
            validate: fn (array $values): ?string => match (true) {
                count($values) === 0 => 'At least one tag is required.',
                count($values) > 3 => 'You can select up to 3 tags.',
                default => null,
            },
        );

        $importClipAction->execute(
            clip: $clipDto,
            user: User::find(0),
            tags: $selectedIds
        );

        return CommandAlias::SUCCESS;
    }
}
