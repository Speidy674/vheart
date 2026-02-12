<?php

declare(strict_types=1);

use App\Http\Controllers\ImageProxyController;

Route::get('/static/proxy', ImageProxyController::class)
    ->middleware([
        'signed',
        'throttle:image-proxy',
    ])
    ->name('image-proxy');
