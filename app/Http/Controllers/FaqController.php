<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Faq\FaqEntry;
use Illuminate\Contracts\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $questions = FaqEntry::query()
            ->whereNowOrPast('published_at')
            ->orderBy('order')
            ->whereLocale('title', app()->getLocale())
            ->get();

        return view('faq', ['questions' => $questions]);
    }
}
