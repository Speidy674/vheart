<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Enums\Broadcaster\BroadcasterConsent;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class PermissionsController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('settings/permissions');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'broadcasterConsent' => ['required', Rule::enum(BroadcasterConsent::class)],
            'state' => ['required', 'boolean'],
        ]);

        $broadcaster = $request->user()->broadcaster()->firstOrCreate([]);
        $consent = $broadcaster->consent ?? collect([]);
        $broadCasterConsent = $request->enum('broadcasterConsent', BroadcasterConsent::class);
        if ($request->boolean('state') === true && ! in_array($broadCasterConsent, $consent->toArray())) {
            $consent->push($broadCasterConsent);
        } else {
            $consent = $consent->filter(fn ($item) => $item !== $broadCasterConsent);
        }

        $broadcaster->update([
            'consent' => $consent,
        ]);

        return back();
    }
}
