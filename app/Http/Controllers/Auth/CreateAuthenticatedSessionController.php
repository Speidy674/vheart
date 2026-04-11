<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;

/**
 * Shows the Login Prompt
 */
#[Middleware('guest')]
class CreateAuthenticatedSessionController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('auth.login', [
            'status' => $request->session()->get('status'),
        ]);
    }
}
