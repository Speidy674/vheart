<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\ImportClipAction;
use App\Models\User;
use App\Services\Twitch\TwitchService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:import-top-clips-from-twitch')]
#[Description('Import top 100 clips from Twitch (Just Chatting)')]
class ImportTopClipsFromTwitchCommand extends Command
{
    private const int JUST_CHATTING_CATEGORY_ID = 509658;

    private const int LIMIT = 100;

    public function handle(TwitchService $twitchService, ImportClipAction $importClipAction): int
    {
        $clips = $twitchService->asApp()->getClips([
            'game_id' => self::JUST_CHATTING_CATEGORY_ID,
            'first' => self::LIMIT,
        ]);

        if ($clips === []) {
            $this->warn('No clips returned from Twitch.');

            return self::FAILURE;
        }

        $user = User::first();

        foreach ($clips as $clip) {
            $importClipAction->execute($clip, $user);

            $this->info("Imported: {$clip->title}");
        }

        return self::SUCCESS;
    }
}
