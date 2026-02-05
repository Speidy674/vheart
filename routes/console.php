<?php

declare(strict_types=1);

use App\Services\Twitch\Data\GameDto;
use App\Services\Twitch\TwitchService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

/**
 * Request Categories in a batch of at most 100 items every 5 minutes.
 *
 * This reduces the amount of API calls we have to do and we should be eventually consistent within 5 minute frames
 */
Schedule::call(function (TwitchService $twitchService, App\Actions\ImportCategoryAction $importCategoryAction) {
    $missingGames = App\Models\Clip::query()
        ->whereDoesntHave('game')
        ->distinct()
        ->limit(100)
        ->pluck('game_id');

    if ($missingGames->isEmpty()) {
        Log::debug('No categories with missing data.');

        return;
    }

    $games = $twitchService->get(App\Services\Twitch\TwitchEndpoints::GetGames, [
        'id' => $missingGames->toArray(),
    ]);

    collect($games)->each(function (GameDto $game) use ($importCategoryAction) {
        $importCategoryAction->execute($game);
    });

    Log::debug('Fetched missing Categories from Twitch', [
        'total' => $missingGames->count(),
    ]);
})
    ->name('Fetch missing Category/Game data')
    ->everyFiveMinutes();
