<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use App\Models\User;
use Filament\PanelProvider as AbstractFilamentPanelProvider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use SocialiteProviders\Manager\OAuth2\AbstractProvider as AbstractSocialiteProvider;

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->beforeEach(function () {
        $this->withoutVite();
    })
    ->in('Feature');

/*
 * Architecture Tests to enforce a certain level of consistency (and quality)
 */
arch('only traits in traits folder')
    ->expect('App\*\Traits')
    ->toBeTraits();

// It avoids the usage of die, var_dump, and similar functions, and ensures we are not using deprecated PHP functions.
// https://github.com/pestphp/pest/blob/4.x/src/ArchPresets/Php.php
arch()->preset()->php();

// It ensures we are not using code that could lead to security vulnerabilities.
// We may use sha1 for cache keys though
// https://github.com/pestphp/pest/blob/4.x/src/ArchPresets/Security.php
arch()->preset()->security()->ignoring('sha1');

// It ensures the projects structure is following the well-known Laravel conventions
// https://github.com/pestphp/pest/blob/4.x/src/ArchPresets/Laravel.php
arch()->preset()->laravel()
    ->ignoring([
        "App\Providers\Filament", // Filament has different base class
        "App\Providers\Socialite", // Custom Socialite Providers
        "App\Services", // Services may not follow the strict laravel conventions (yet)
        "App\Enums\Traits", // should probably organize it better but this has to work for now
        "App\Http\Resources", // Resources may be used in models, preset was created before laravel had that attribute.
    ]);

// Filament
arch('filament specifics')->expect('App\Providers\Filament')
    ->toHaveSuffix('PanelProvider')
    ->toExtend(AbstractFilamentPanelProvider::class);

// Socialite Providers
arch('socialite specifics')->expect('App\Providers\Socialite')
    ->toHaveSuffix('SocialiteProvider')
    ->toExtend(AbstractSocialiteProvider::class);

// Services
arch()
    ->expect('App\Services')
    ->not->toUse('App\Http\Controllers')
    ->not->toUse('App\Http\Requests')
    ->not->toUse(['dd', 'dump', 'ray', 'var_dump']);
arch()
    ->expect('App\Services\*\Data')
    ->classes()
    ->toHaveSuffix('Dto')
    ->toBeReadonly()
    ->toExtendNothing();
arch()
    ->expect('App\Services\*\Exceptions')
    ->classes()
    ->toHaveSuffix('Exception')
    ->toExtend(Exception::class);

/**
 * mocks a successful socialite response from twitch with $user as data source
 */
function mockTwitchUser(User $user): void
{
    $socialiteUser = new SocialiteUser();
    $socialiteUser->id = $user->id;
    $socialiteUser->name = $user->name;
    $socialiteUser->token = 'mock-access-token';
    $socialiteUser->refreshToken = 'mock-refresh-token';
    $socialiteUser->user = ['created_at' => now()->subYear()->toIso8601String()];

    Socialite::shouldReceive('driver->user')->andReturn($socialiteUser);
}
