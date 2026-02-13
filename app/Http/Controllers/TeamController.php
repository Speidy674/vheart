<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\Role\RoleUserListResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $roles = Role::query()
            ->with('users')
            ->orderBy('weight', 'desc')
            ->orderBy('name')
            ->where('public', true)
            ->get();

        return Inertia::render('team', [
            'roles' => $roles->toResourceCollection(RoleUserListResource::class),
        ]);
    }
}
