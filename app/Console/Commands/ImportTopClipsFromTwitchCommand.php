<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\ImportClipAction;
use App\Models\User;
use App\Services\Twitch\TwitchService;
use Illuminate\Console\Command;

class ImportTopClipsFromTwitchCommand extends Command
{
    private const int JUST_CHATTING_CATEGORY_ID = 509658;

    private const int LIMIT = 100;

    protected $signature = 'app:import-top-clips-from-twitch';

    protected $description = 'Import top 100 clips from Twitch (Just Chatting)';

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
