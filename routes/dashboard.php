<?php

declare(strict_types=1);

use App\Http\Middleware\BroadcasterDashboardAcces;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function (Request $request) {
        return Redirect::route('dashboard.main', $request->user()->id);
    })->name('dashboard');

    Route::get('/dashboard/{user}', function (User $user, Request $request) {
        return Inertia::render('dashboard', ['streamer' => $user->toResource(UserResource::class)]);

    })->middleware(BroadcasterDashboardAcces::class)
        ->missing(function () {
            return Redirect::route('home');
        })->name('dashboard.main');

    Route::get('/dashboard/{user}/clips', function (User $user, Request $request) {
        return Inertia::render('dashboard', ['streamer' => $user->toResource(UserResource::class)]);

    })->middleware(BroadcasterDashboardAcces::class)
        ->missing(function () {
            return Redirect::route('home');
        })->name('dashboard.clips');

    Route::get('/dashboard/{user}/permissions', function (User $user, Request $request) {
        return Inertia::render('dashboard', ['streamer' => $user->toResource(UserResource::class)]);

    })->middleware(BroadcasterDashboardAcces::class)
        ->missing(function () {
            return Redirect::route('home');
        })->name('dashboard.permissions');
});
