<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Email;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyEmailRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Routing\Controllers\HasMiddleware;

class EmailVerificationController extends Controller implements HasMiddleware
{
    public function __invoke(VerifyEmailRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', ['verified' => true]));
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(route('dashboard', ['verified' => true]));
    }

    public static function middleware(): array
    {
        return [
            'auth:web',
            'signed',
            'throttle:6,1',
        ];
    }
}
