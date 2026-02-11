<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\ExternalContentProxyType;
use App\Models\Clip;
use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Vite;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it can determine type from model instance', function () {
    $game = Game::factory()->make();
    $clip = Clip::factory()->make();
    $user = User::factory()->make();

    expect(ExternalContentProxyType::fromModel($game))->toBe(ExternalContentProxyType::TwitchCategory)
        ->and(ExternalContentProxyType::fromModel($clip))->toBe(ExternalContentProxyType::TwitchClip)
        ->and(ExternalContentProxyType::fromModel($user))->toBe(ExternalContentProxyType::TwitchUser);
});

test('it generates correct proxy url for standard models', function () {
    $clip = Clip::factory()->create(['twitch_id' => 'clipper123']);
    $user = User::factory()->create(['id' => 55]);

    $clipUrl = ExternalContentProxyType::toProxyUrl($clip);
    $userUrl = ExternalContentProxyType::toProxyUrl($user);

    expect($clipUrl)->toContain('/static-external/clip/clipper123.jpg')
        ->and($userUrl)->toContain('/static-external/user/55.png');
});

test('it handles the special user id 0 case', function () {
    Vite::shouldReceive('asset')
        ->once()
        ->with('resources/images/png/cat.png')
        ->andReturn('http://localhost/build/cat.png');

    $user = User::factory()->make(['id' => 0]);

    $url = ExternalContentProxyType::toProxyUrl($user);

    expect($url)->toBe('http://localhost/build/cat.png');
});

test('it generates dynamic size urls for categories', function () {
    $game = Game::factory()->create(['id' => 999]);

    $urlResized = ExternalContentProxyType::toProxyUrl($game, 50, 50);
    $urlStandard = ExternalContentProxyType::toProxyUrl($game);

    expect($urlResized)->toContain('/static-external/category/999-50x50.jpg')
        ->and($urlStandard)->toContain('/static-external/category/999.jpg');
});

test('it correctly resolves resource url from database', function () {
    $clip = Clip::factory()->create([
        'twitch_id' => 'some-slug',
        'thumbnail_url' => 'https://example.com/img.jpg',
    ]);

    $clip->broadcaster()->update([
        'clip_permission' => true,
    ]);

    $resolvedUrl = ExternalContentProxyType::TwitchClip->getResource('some-slug');
    expect($resolvedUrl)->toBe('https://example.com/img.jpg');
});

test('it resolves and replaces dynamic dimensions for categories', function () {
    Game::factory()->create([
        'id' => 123,
        'box_art' => 'https://example.com/box-{width}x{height}.jpg',
    ]);

    $resolvedWithDims = ExternalContentProxyType::TwitchCategory->getResource('123-200x300');
    expect($resolvedWithDims)->toBe('https://example.com/box-200x300.jpg');

    $resolvedRaw = ExternalContentProxyType::TwitchCategory->getResource('123');
    expect($resolvedRaw)->toBe('https://example.com/box-{width}x{height}.jpg');
});

test('it throws model not found exception if identifier does not exist', function () {
    ExternalContentProxyType::TwitchUser->getResource('999999');
})->throws(ModelNotFoundException::class);
