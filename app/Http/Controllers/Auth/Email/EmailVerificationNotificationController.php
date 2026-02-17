<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Email;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class EmailVerificationNotificationController extends Controller implements HasMiddleware
{
    public function __invoke(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new JsonResponse(status: 204)
                : redirect()->intended(route('dashboard'));
        }

        $request->user()->sendEmailVerificationNotification();

        return $request->wantsJson()
            ? new JsonResponse(status: 202)
            : back()->with('status', __('auth.verification.sent'));
    }

    public static function middleware(): array
    {
        return [
            'auth:web',
            'throttle:6,1',
        ];
    }
}
