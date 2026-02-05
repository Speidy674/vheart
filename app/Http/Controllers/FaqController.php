<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Faq\FaqEntry;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Throwable;

class FaqController extends Controller
{
    /**
     * @throws Throwable
     */
    public function index(): InertiaResponse
    {
        return Inertia::render('Faq/Index', [
            'faq' => FaqEntry::query()
                ->whereNowOrPast('published_at')
                ->orderBy('order')
                ->whereLocale('title', app()->getLocale())
                ->get()->toResourceCollection(),
        ]);
    }
}
