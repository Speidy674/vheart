<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ImageProxyRequest;
use Illuminate\Support\Facades\Http;

/**
 * We act as a proxy to hide the user ip from twitch/youtube
 * We use Cloudflare to prevent additional load on our end via caching
 */
class ImageProxyController extends Controller
{
    public const int STREAM_BUFFER_SIZE = 8192;

    public function __invoke(ImageProxyRequest $request)
    {
        $url = $request->string('url')->toString();

        $response = Http::withOptions(['stream' => true])
            ->timeout(5)
            ->get($url);

        if ($response->failed()) {
            abort(404);
        }

        $contentType = $response->header('Content-Type');
        if (! str_starts_with($contentType, 'image/')) {
            abort(415);
        }

        $headers = [
            'Content-Type' => $contentType,
            'Content-Length' => $response->header('Content-Length'),
            'ETag' => $response->header('ETag'),
            'Cache-Control' => 'public, max-age=31536000, s-maxage=31536000, immutable',
        ];

        return response()->stream(function () use ($response) {
            $body = $response->toPsrResponse()->getBody();

            while (! $body->eof()) {
                echo $body->read(self::STREAM_BUFFER_SIZE);
            }
        }, 200, array_filter($headers));
    }
}
