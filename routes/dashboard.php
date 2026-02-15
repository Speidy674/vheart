<?php

declare(strict_types=1);

use App\Http\Middleware\BroadcasterDashboard;
use App\Models\Clip;
use App\Models\Scopes\ClipPermissionScope;
use App\Models\User;
use App\Services\Twitch\TwitchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {

    $twitchService = new TwitchService;
    $twitchService->onUserTokenRefresh(function ($token) {
        session()->put('twitch_access_token', $token);
    });

    Route::get('/dashboard', function (Request $request) {
        return Redirect::route('dashboard.main', $request->user()->id);
    })->name('dashboard');

    Route::get('/dashboard/{user}', function (User $user, Request $request) {
        $localUser = $request->user();

        return Inertia::render('dashboard');

    })->middleware(BroadcasterDashboard::class)
        ->missing(function () {
            return Redirect::route('home');
        })->name('dashboard.main');

    Route::get('/dashboard/{user}/clips', function (User $user, Request $request) {
        $localUser = $request->user();

        return Inertia::render('dashboard/clips', [
            'clips' => Inertia::scroll(static function () use ($user) {
                $clip = Clip::query()
                    ->withCount(['votes' => fn ($q) => $q->where('voted', true)->where('type', App\Enums\ClipVoteType::Public)])
                    ->orderByDesc('id')
                    ->where('broadcaster_id', $user->id)
                    ->withoutGlobalScope(ClipPermissionScope::class)
                    ->cursorPaginate();

                return $clip->toResourceCollection();
            }),
        ]);
    })->middleware(BroadcasterDashboard::class)
        ->missing(function () {
            return Redirect::route('home');
        })->name('dashboard.clips');
});
