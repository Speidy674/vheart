<?php

declare(strict_types=1);

namespace App\Http\Controllers\Broadcaster;

use App\Enums\FeatureFlag;
use App\Http\Controllers\Controller;
use App\Http\Requests\Broadcaster\OnboardingRequest;
use App\Models\Broadcaster\Broadcaster;
use App\Support\FeatureFlag\Feature;

class OnboardingSubmitController extends Controller
{
    public function __invoke(OnboardingRequest $request)
    {
        Broadcaster::updateOrCreate(['id' => auth()->user()->id], [
            'consent' => $request->array('consent'),
            'submit_user_allowed' => $request->boolean('everyone'),
            'submit_vip_allowed' => $request->boolean('vips'),
            'submit_mods_allowed' => $request->boolean('moderators'),
            'onboarded_at' => now(),
        ]);

        $fallbackRoute = route(
            Feature::isActive(FeatureFlag::UserDashboard)
                ? 'dashboard'
                : 'home'
        );

        return redirect()->intended($fallbackRoute);
    }
}
