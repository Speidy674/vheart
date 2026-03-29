<?php

declare(strict_types=1);

namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImprintController extends Controller
{
    public function __invoke(Request $request)
    {
        $locale = app()->getLocale();

        return view('legal', ['locale' => $locale, 'type' => 'imprint']);
    }
}
