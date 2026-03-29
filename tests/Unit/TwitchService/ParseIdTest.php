<?php

declare(strict_types=1);

use App\Services\Twitch\TwitchClient;
use App\Services\Twitch\TwitchService;

beforeEach(function (): void {
    $this->service = new TwitchService(
        client: Mockery::mock(TwitchClient::class),
    );
});

it('parses clip ids from embed URLs', function (): void {
    expect($this->service->parseClipId(
        'https://clips.twitch.tv/embed?clip=AttractiveAgreeableOxNononoCat-rI2mENW1153C9KU-&parent=example.com'
    ))->toBe('AttractiveAgreeableOxNononoCat-rI2mENW1153C9KU-');
});

it('parses clip ids from channel clip URLs', function (): void {
    expect($this->service->parseClipId(
        'https://www.twitch.tv/lirik/clip/AttractiveAgreeableOxNononoCat-rI2mENW1153C9KU-'
    ))->toBe('AttractiveAgreeableOxNononoCat-rI2mENW1153C9KU-');
});

it('parses clip ids from channel clip URLs with query params', function (): void {
    expect($this->service->parseClipId(
        'https://www.twitch.tv/lirik/clip/AttractiveAgreeableOxNononoCat-rI2mENW1153C9KU-?filter=clips&range=30d'
    ))->toBe('AttractiveAgreeableOxNononoCat-rI2mENW1153C9KU-');
});

it('parses clip ids from clips.twitch.tv URLs', function (): void {
    expect($this->service->parseClipId(
        'https://clips.twitch.tv/AttractiveAgreeableOxNononoCat-rI2mENW1153C9KU-'
    ))->toBe('AttractiveAgreeableOxNononoCat-rI2mENW1153C9KU-');
});

it('parses raw clip ids', function (): void {
    expect($this->service->parseClipId(
        'AttractiveAgreeableOxNononoCat-rI2mENW1153C9KU-'
    ))->toBe('AttractiveAgreeableOxNononoCat-rI2mENW1153C9KU-');
});

it('returns null for video URLs', function (): void {
    expect($this->service->parseClipId('https://www.twitch.tv/videos/123456789'))->toBeNull();
});

it('returns null for channel URLs', function (): void {
    expect($this->service->parseClipId('https://www.twitch.tv/shroud'))->toBeNull();
});

it('returns null for hyphenated channel URLs', function (): void {
    expect($this->service->parseClipId('https://www.twitch.tv/riot-games'))->toBeNull();
});

it('returns null for empty clips.twitch.tv root', function (): void {
    expect($this->service->parseClipId('https://clips.twitch.tv/'))->toBeNull();
});

it('returns null for plain text', function (): void {
    expect($this->service->parseClipId('never gonna give you up'))->toBeNull();
});

it('returns null for empty string', function (): void {
    expect($this->service->parseClipId(''))->toBeNull();
});

it('returns null for an incomplete slug', function (): void {
    expect($this->service->parseClipId('https://clips.twitch.tv/IncompleteSlugHere'))->toBeNull();
});
