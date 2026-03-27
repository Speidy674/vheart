<?php

declare(strict_types=1);

use App\Enums\FeatureFlag;
use App\Http\Controllers\ClipSubmitController;
use App\Http\Controllers\ClipVoteController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TeamController;
use App\Models\Clip;
use App\Support\FeatureFlag\Feature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', static function (Request $request) {

    // Have to redirect as about us is currently being worked on by someone else
    if (Feature::isActive(FeatureFlag::AboutUsAsIndex)) {
        return redirect()->route('about');
    }

    $bestRated = Clip::query()
        ->where('created_at', '>', now()->subDays(30))
        ->whereHas('votes', fn ($q) => $q->where('voted', true))
        ->with('tags')
        ->withAbsoluteVoteCount()
        ->orderByDesc('votes_count')
        ->limit(10)
        ->get();

    $discover = Clip::query()
        ->with('tags')
        ->withAbsoluteVoteCount()
        ->orderByDesc('created_at')
        ->orderByDesc('id')
        ->cursorPaginate(perPage: 42);

    if ($request->ajax()) {
        return response(
            view('components.clips.preview-list', ['clips' => $discover])->render(),
            headers: [
                'X-Next-Page' => $discover->nextPageUrl(),
            ]);
    }

    return view('index', [
        'bestRated' => $bestRated,
        'discover' => $discover,
    ]);
})
    ->name('home');

Route::get('/about-us', static function () {
    $settings = [
        'donationUrl' => 'https://www.betterplace.org/de/fundraising-events/55712-vheart-fuerdiesuessmaeuse',
        'partnerIcon' => null,
        'youtubeUrl' => 'https://www.youtube-nocookie.com/embed/videoseries?list=UUUefW5IjMaQS_ZFaG4VZi9A',
    ];

    return Inertia::render('welcome', $settings);
})->name('home');

Route::get('/imprint', function () {
    $locale = app()->getLocale();

    return view('legal', ['locale' => $locale, 'type' => 'imprint']);
});

Route::get('/privacy', function () {
    $locale = app()->getLocale();

    return view('legal', ['locale' => $locale, 'type' => 'privacy']);
});

Route::get('/faq', [FaqController::class, 'index'])->name('faq');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::feature(FeatureFlag::ClipSubmission)->group(function () {
        Route::get('/submit', [ClipSubmitController::class, 'create'])->name('submitclip.create');
        Route::post('/submit', [ClipSubmitController::class, 'store'])->name('submitclip.store');
    });

    Route::feature(FeatureFlag::ClipVoting)->group(function () {
        Route::get('/vote', [ClipVoteController::class, 'create'])->name('vote');
        Route::post('/vote', [ClipVoteController::class, 'store'])->middleware('throttle:10,1')->name('vote.submit');
    });

    Route::feature(FeatureFlag::Reports)->group(function () {
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    });
});

Route::get('/team', TeamController::class)->name('team');

Route::get('/about-us', function () {
    $settings = [
        'donationUrl' => 'https://www.betterplace.org/de/fundraising-events/55712-vheart-fuerdiesuessmaeuse',
        'partnerIcon' => null,
    ];

    return Inertia::render('about', $settings);
})->name('about');

Route::get('/locales', static function (Request $request) {
    $lang = $request->input('locale', 'en');

    if (! array_key_exists($lang, Config::get('app.locales'))) {
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
})->name('locales');

require __DIR__.'/dashboard.php';
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
