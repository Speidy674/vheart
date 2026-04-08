<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function __invoke(Request $request): View
    {
        $roles = Role::query()
            ->where('public', true)
            ->orderBy('weight', 'desc')
            ->orderBy('id')
            ->with(['users' => fn ($builder) => $builder
                ->orderBy('id')] // i think this is the fairest way to sort it
            )
            ->get();

        return view('team.index', ['roles' => $roles]);
    }
}
