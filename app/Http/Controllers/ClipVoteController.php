<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Clips\CompilationStatus;
use App\Enums\ClipVoteType;
use App\Enums\Permission;
use App\Models\Clip;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClipVoteController extends Controller
{
    private const string SESSION_QUEUE_KEY = 'CLIP_VOTE_QUEUE';

    private const int QUEUE_SIZE = 20;

    /**
     * Show the form for creating the resource.
     */
    public function create(Request $request)
    {
        $user = $request->user();
        $session = $request->session();

        return Inertia::render('evaluateclips', [
            'history' => Inertia::optional(function () use ($user) {
                $lastVotes = Vote::where('user_id', $user->id)->limit(5)
                    ->with(['clip' => function (BelongsTo $query) {
                        $query->select(['id', 'twitch_id', 'title'])
                            ->withCount(['votes as public_votes' => function (Builder $query) {
                                $query->where('type', ClipVoteType::Public);
                            }]);
                    }])
                    ->select(['id', 'clip_id', 'voted', 'created_at'])
                    ->orderBy('id', 'desc')
                    ->get();

                return $lastVotes;
            }),
            'clip' => Inertia::optional(function () use ($user, $session) {

                $clipIdQueue = [];

                if ($session->has(self::SESSION_QUEUE_KEY)) {
                    $clipSessionQueue = $session->get(self::SESSION_QUEUE_KEY);
                    if (is_array($clipSessionQueue) && ! empty($clipSessionQueue)) {
                        $clipIdQueue = $clipSessionQueue;
                    }
                }

                if (empty($clipIdQueue)) {

                    $clips = Clip::whereDoesntHave('votes', function (Builder $query) use ($user) {
                        return $query->where('user_id', $user->id);
                    })->whereNot('broadcaster_id', $user->id)
                        ->whereNot('submitter_id', $user->id)
                        ->whereDoesntHave('compilations', function (Builder $query) {
                            return $query->whereIn('compilations.status', CompilationStatus::getVoteDisabledCases());
                        })->select(['id'])
                        ->inRandomOrder()
                        ->limit(value: self::QUEUE_SIZE)
                        ->get();

                    // TODO clip permission checken

                    $clipIdQueue = $clips->pluck('id')->toArray();

                    $session->put(self::SESSION_QUEUE_KEY, $clipIdQueue);
                }

                $clipIdToVote = $clipIdQueue[0];

                $clip = Clip::select(['id', 'twitch_id', 'title'])
                    ->withCount(['votes as public_votes' => function (Builder $query) {
                        $query->where('type', ClipVoteType::Public);
                    }])->find($clipIdToVote);

                return $clip;
            }),
        ]);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'clip' => ['required', 'exists:clips,id'],
            'voted' => ['required', 'bool'],
        ]);

        $session = $request->session();

        if ($session->has(key: self::SESSION_QUEUE_KEY)) {
            $clipSessionQueue = $session->get(self::SESSION_QUEUE_KEY);
            $clipIdtoVote = null;
            if (is_array($clipSessionQueue) && ! empty($clipSessionQueue)) {
                $clipIdtoVote = $clipSessionQueue[0];
            }

            if (! empty($clipIdtoVote) && $clipIdtoVote === $data['clip']) {
                array_shift($clipSessionQueue);
                $session->put(self::SESSION_QUEUE_KEY, value: $clipSessionQueue);
            }
        }

        $clip = Clip::whereDoesntHave('compilations', function (Builder $query) {
            return $query->whereIn('compilations.status', CompilationStatus::getVoteDisabledCases());
        })->find($data['clip']);

        if (empty($clip)) {
            return $this->create($request);
        }

        $voteType = ClipVoteType::Public;
        if ($request->user()->can(Permission::JuryVote)) {
            $voteType = ClipVoteType::Jury;
        }
        $clip->votes()->updateOrCreate([
            'user_id' => $request->user()->id,
        ], [
            'voted' => $data['voted'],
            'type' => $voteType,
        ]);

        return $this->create($request);
    }
}
