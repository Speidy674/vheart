<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ChangeLanguageController extends Controller
{
    public function __invoke(Request $request)
    {
        $lang = $request->input('locale', 'en');

        if (! array_key_exists((string) $lang, Config::get('app.locales'))) {
            if (! $request->expectsJson()) {
                return redirect()->back()->withErrors([
                    'locale' => 'Invalid locale selected',
                ]);
            }

            abort(422);
        }

        app()->setLocale($lang);
        session()?->put('locale', $lang);

        if ($request->expectsJson()) {
            return new JsonResponse([
                'message' => __('Ok!'),
            ], 200);
        }

        return redirect()->back();
    }
}
