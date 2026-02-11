<?php

declare(strict_types=1);

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Twitch\TwitchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {

    $twitchService = new TwitchService();
    $twitchService->onUserTokenRefresh(function ($token) {
        session()->put('twitch_access_token', $token);
    });

    Route::get('/dashboard/{user}', function (User $user, Request $request) use ($twitchService) {
        $localUser = $request->user();
        // User is self
        // user is mod and allowed

        if ($user->id === $localUser->id || $twitchService->asUser($localUser, session()?->get('twitch_access_token'))->isModeratorFor($user)) {
            return Inertia::render('dashboard', ['streamer' => $user->toResource(UserResource::class)]);
        }

        return Redirect::route('home');

    })->missing(function () {
        return Redirect::route('home');
    })->name('dashboard.main');
});
