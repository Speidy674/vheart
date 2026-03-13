<?php

declare(strict_types=1);

use App\Casts\TwitchAvatarCast;
use App\Casts\TwitchClipThumbnailCast;

it('encodes and decodes twitch avatars', function (string $url, string $encoded) {
    expect(TwitchAvatarCast::encode($url))->toBe($encoded)
        ->and(TwitchAvatarCast::decode($encoded))->toBe($url);
})->with([
    'standard png' => [
        'https://static-cdn.jtvnw.net/jtv_user_pictures/placeholder-b2ae-42e0-a4a0-bc80dc818542-profile_image-300x300.png',
        'u:p:placeholder-b2ae-42e0-a4a0-bc80dc818542',
    ],
    'avatar with hash' => [
        'https://static-cdn.jtvnw.net/jtv_user_pictures/placeholder-profile_image-8fc178fa1203f78f-300x300.jpeg',
        'u:j:placeholder:8fc178fa1203f78f',
    ],
    'default user avatar' => [
        'https://static-cdn.jtvnw.net/user-default-pictures-uv/placeholder-db81-4b9c-8940-64ed33ccfc7b-profile_image-300x300.png',
        'd:p:placeholder-db81-4b9c-8940-64ed33ccfc7b',
    ],
]);

it('encodes and decodes clip thumbnails', function (string $url, string $encoded, ?string $clipId) {
    expect(TwitchClipThumbnailCast::encode($url, $clipId))->toBe($encoded)
        ->and(TwitchClipThumbnailCast::decode($encoded, $clipId))->toBe($url);
})->with([
    'modern clip format' => [
        'https://static-cdn.jtvnw.net/twitch-clips-thumbnails-prod/placeholder-1eErSWrf0oTe4DNb/426854d0-4790-4214-9dfd-ab39f52dba9a/preview-480x272.jpg',
        'p:x:480x272:{}/426854d0-4790-4214-9dfd-ab39f52dba9a',
        'placeholder-1eErSWrf0oTe4DNb',
    ],
    'vod landscape asset format' => [
        'https://static-cdn.jtvnw.net/twitch-video-assets/twitch-vap-video-assets-prod-us-west-2/placeholder-0064-459c-b019-9d9d1d1a3ff8/landscape/thumb/thumb-0000000000-480x272.jpg',
        'v:x:480x272:placeholder-0064-459c-b019-9d9d1d1a3ff8',
        'some-random-string-that-wont-affect-this',
    ],
    'legacy clip format' => [
        'https://static-cdn.jtvnw.net/twitch-clips/3-PlAceHoLdEr123/AT-cm%7C3-PlAceHoLdEr123-preview-480x272.jpg',
        'c:x:480x272:{}/AT-cm%7C{}',
        '3-PlAceHoLdEr123',
    ],
]);

it('returns input as is if format is unknown', function () {
    $invalidUrl = 'https://example.com/not-a-twitch-url.jpg';

    expect(TwitchAvatarCast::encode($invalidUrl))->toBe($invalidUrl)
        ->and(TwitchAvatarCast::decode($invalidUrl))->toBe($invalidUrl)
        ->and(TwitchClipThumbnailCast::encode($invalidUrl))->toBe($invalidUrl)
        ->and(TwitchClipThumbnailCast::decode($invalidUrl))->toBe($invalidUrl);
});
