<?php

declare(strict_types=1);

use App\Http\Controllers\ExternalContentProxyController;

Route::get('/static-external/{type}/{identifier}.{extension}', ExternalContentProxyController::class)
    ->middleware(['throttle:image-proxy'])
    ->name('static-external');
