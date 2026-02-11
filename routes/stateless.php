<?php

declare(strict_types=1);

use App\Http\Controllers\LocalesController;
use App\Http\Controllers\ExternalContentProxyController;

Route::get('/locales.json', LocalesController::class)
    ->middleware(['throttle:locales', 'cache.headers:public;max_age=3600;s_maxage=3600;stale_while_revalidate=86400;etag'])
    ->name('locales');

Route::get('/static-external/{type}/{identifier}.{extension}', ExternalContentProxyController::class)
    ->middleware(['throttle:image-proxy'])
    ->name('static-external');
