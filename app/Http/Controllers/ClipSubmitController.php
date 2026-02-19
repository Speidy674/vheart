<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\ImportClipAction;
use App\Http\Requests\SubmitClipRequest;
use App\Models\Clip;
use App\Models\Clip\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ClipSubmitController extends Controller
{
    public function create(): Response
    {
        $tags = Tag::all();

        return Inertia::render('submitclip', [
            'tags' => $tags->toResourceCollection(),
        ]);
    }

    public function store(SubmitClipRequest $request, ImportClipAction $importClipAction): Response
    {
        Gate::authorize('submit', Clip::class);

        $clipInfo = $request->clipInfo;

        User::updateOrCreate([
            'id' => $clipInfo->creator_id,
        ], [
            'name' => $clipInfo->creator_name,
        ]);

        $importClipAction->execute(
            $clipInfo,
            $request->user(),
            $request->validated('tags') ?? []
        );

        return $this->create()
            ->with('submit_ok', true)
            ->with('submit_message', __('sendinclip.flash.submitted'));
    }
}
