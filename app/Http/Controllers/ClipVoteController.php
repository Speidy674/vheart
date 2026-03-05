<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ClipVoteType;
use App\Enums\Permission;
use App\Models\Clip;
use App\Models\Scopes\ClipPermissionScope;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClipVoteController extends Controller
{
    private const string SESSION_QUEUE_KEY = 'CLIP_VOTE_QUEUE';

    private const int QUEUE_SIZE = 20;

    /**
     * Show the form for creating the resource.
     */
    public function create(Request $request)
    {
        $clipIdToVote = $this->getNextClipId($request);

        $clip = Clip::query()
            ->withoutGlobalScope(ClipPermissionScope::class)
            ->withPublicVoteCount()
            ->find($clipIdToVote);

        return view('clips.vote', [
            'clip' => $clip,
        ]);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'voted' => ['required', 'bool'],
        ]);

        $vote = $request->boolean('voted');
        $clipId = $this->getNextClipId($request);
        $this->shiftClipQueue($request);

        $clip = Clip::query()
            ->whereNotPublished()
            ->find($clipId);

        if ($clip) {
            $voteType = $request->user()->can(Permission::JuryVote)
                ? ClipVoteType::Jury
                : ClipVoteType::Public;

            $clip->votes()->updateOrCreate([
                'user_id' => $request->user()->id,
            ], [
                'voted' => $vote,
                'type' => $voteType,
            ]);
        }

        if (! $request->expectsJson()) {
            return back(fallback: route('vote'));
        }

        $clip = Clip::query()
            ->withoutGlobalScope(ClipPermissionScope::class)
            ->withPublicVoteCount()
            ->find($this->getNextClipId($request));

        return new JsonResponse($clip?->toResource());
    }

    /**
     * Shifts the clip voting Queue
     */
    protected function shiftClipQueue(Request $request): void
    {
        $clipQueue = $this->getVoteQueue($request);

        if (! $clipQueue || $clipQueue === []) {
            return;
        }

        array_shift($clipQueue);
        $request->session()->put(self::SESSION_QUEUE_KEY, $clipQueue);
    }

    /**
     * Get the clip vote queue, if the queue is empty or does not exist we will also generate it here.
     */
    protected function getVoteQueue(Request $request): array
    {
        $user = $request->user();
        $session = $request->session();
        $clips = $session->get(self::SESSION_QUEUE_KEY, []);

        if ($clips === []) {
            $clips = Clip::query()
                ->whereNot('submitter_id', $user->id)
                ->whereNot('broadcaster_id', $user->id)
                ->whereNot('creator_id', $user->id)
                ->whereNoVotesFrom($user)
                ->whereNotPublished()
                ->inRandomOrder()
                ->limit(self::QUEUE_SIZE)
                ->pluck('id')
                ->toArray();

            if ($clips !== []) {
                $session->put(self::SESSION_QUEUE_KEY, $clips);
            }
        }

        return $clips;
    }

    /**
     * Get the next clip id in the queue.
     *
     * Will return `null` if there is nothing the user can vote on anymore.
     */
    protected function getNextClipId(Request $request): ?int
    {
        $voteQueue = $this->getVoteQueue($request);

        if (count($voteQueue) !== 0) {
            return $voteQueue[0];
        }

        return null;
    }
}
