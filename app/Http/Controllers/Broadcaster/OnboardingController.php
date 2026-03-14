<?php

declare(strict_types=1);

namespace App\Http\Controllers\Broadcaster;

use App\Enums\FeatureFlag;
use App\Http\Controllers\Controller;
use App\Models\Broadcaster\Broadcaster;
use App\Support\FeatureFlag\Feature;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function __invoke(Request $request)
    {
        if (Broadcaster::query()->where('id', $request->user()->id)->exists()) {
            return redirect()->route(Feature::isActive(FeatureFlag::UserDashboard) ? 'dashboard' : 'home');
        }

        return view('broadcaster.onboarding');
    }
}
