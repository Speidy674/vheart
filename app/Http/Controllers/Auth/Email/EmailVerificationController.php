<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Email;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyEmailRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware('signed')]
#[Middleware('auth:web')]
#[Middleware('throttle:6,1')]
class EmailVerificationController extends Controller
{
    public function __invoke(VerifyEmailRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home', ['verified' => true]));
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(route('home', ['verified' => true]));
    }
}
