<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\ImportClipAction;
use App\Models\Category;
use App\Models\Clip;
use App\Models\Clip\Tag;
use App\Models\User;
use App\Services\Twitch\Exceptions\TwitchApiException;
use App\Services\Twitch\TwitchEndpoints;
use App\Services\Twitch\TwitchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ClipSubmitController extends Controller
{
    private TwitchService $twitchService;

    public function __construct()
    {
        $this->twitchService = new TwitchService;
        $this->twitchService->onUserTokenRefresh(function ($token) {
            session()->put('twitch_access_token', $token);
        });
    }

    /**
     * Show the form for creating the resource.
     */
    public function create(): Response
    {
        $tags = Tag::all();

        return Inertia::render('submitclip', [
            'tags' => $tags->toResourceCollection(),
        ]);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request, ImportClipAction $importClipAction): Response
    {
        Gate::authorize('submit', Clip::class);

        $data = $request->validate([
            'clip_url' => ['required', 'string', 'url'],
            'tags' => ['required', 'array', 'min:1', 'max:3'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'is_anonymous' => ['sometimes', 'accepted'],
        ]);

        $clipId = $this->twitchService->parseClipId($data['clip_url']);

        if (! $clipId) {
            $this->returnError('sendinclip.errors.clip_not_found');
        }

        $tagIds = $data['tags'] ?? [];
        $isAnonymous = ($data['is_anonymous'] ?? 'off') === 'on';

        $user = $request->user();

        if (Clip::query()->where('twitch_id', $clipId)->exists()) {
            $this->returnError('sendinclip.errors.clip_already_known');
        }

        $clipInfo = $this->twitchService->asUser($user, $this->getUserToken())->getClipByID($clipId);

        if (! $clipInfo) {
            $this->returnError('sendinclip.errors.clip_not_found');
        }

        // Check if the Category is Site-Banned
        $isCategoryBanned = Category::query()
            ->where('is_banned', true)
            ->where('id', $clipInfo->game_id)
            ->exists();

        if ($isCategoryBanned) {
            $this->returnError('sendinclip.errors.game_blocked');
        }

        // Check if the Broadcaster is even registered (block if not)
        $broadcasterUser = User::query()
            ->where('id', $clipInfo->broadcaster_id)
            ->where('users.clip_permission', true)
            ->first();

        if ($broadcasterUser === null) {
            $this->returnError('sendinclip.errors.broadcaster_not_allowed');
        }

        // Check if user is blacklisted by Creator
        $isUserBlackedListed = $broadcasterUser
            ->broadcasterUserFilter()
            ->where('filter_id', $user->id)
            ->where('allowed', false)
            ->exists();

        if ($isUserBlackedListed) {
            $this->returnError('sendinclip.errors.user_not_allowed_for_broadcaster');
        }

        // Check Broadcaster Rules
        $broadcasterRules = $broadcasterUser->rules ?? [];
        $isUserAllowed = empty($broadcasterRules) || $clipInfo->broadcaster_id === $user->id;

        // Check if user is in explicit Allow-list (allow if yes)
        if (! $isUserAllowed && in_array('userAllowList', $broadcasterRules, true)) {
            $isUserAllowed = $broadcasterUser
                ->broadcasterUserFilter()
                ->where('user_id', $user->id)
                ->where('allowed', true)
                ->exists();
        }

        // Check if Broadcaster has enabled Mod Allow-list and if the User is on it
        if (! $isUserAllowed && in_array('userAllowMods', $broadcasterRules, true)) {
            $isUserAllowed = $this->twitchService->asUser($user, $this->getUserToken())->isModeratorFor($broadcasterUser);
        }

        // Check if Broadcaster has enabled VIP Allow-list and if the User is on it
        if (! $isUserAllowed && in_array('userAllowVips', $broadcasterRules, true)) {
            try {
                $vipInfos = $this->twitchService->asUser($broadcasterUser)->onUserTokenRefresh()->get(TwitchEndpoints::GetVIPs, [
                    'user_id' => $user->id,
                    'broadcaster_id' => $clipInfo->broadcaster_id,
                ]);

                $isUserAllowed = ! empty($vipInfos['data']);
            } catch (TwitchApiException $th) {
                report($th);
            }
        }

        // If user was not allowed by any of the previous checks, deny
        if (! $isUserAllowed) {
            $this->returnError('sendinclip.errors.user_not_allowed_for_broadcaster');
        }

        // Check if Broadcaster has banned the Category
        $isGameBlackListed = $broadcasterUser
            ->broadcasterCategoryFilter()
            ->where('filter_id', $clipInfo->game_id)
            ->where('allowed', false)
            ->exists();

        if ($isGameBlackListed) {
            $this->returnError('sendinclip.errors.game_blocked');
        }

        // Check if Broadcaster has enabled Category Whitelist (>0 entries)
        $hasOneGameWhiteListed = $broadcasterUser
            ->broadcasterCategoryFilter()
            ->where('allowed', true)
            ->exists();
        $isGameWhiteListed = ! $hasOneGameWhiteListed;

        // If whitelist has entries, check if category is whitelisted
        if ($hasOneGameWhiteListed) {
            $isGameWhiteListed = $broadcasterUser
                ->broadcasterCategoryFilter()
                ->where('filter_id', $clipInfo->game_id)
                ->where('allowed', true)
                ->exists();
        }

        if ($hasOneGameWhiteListed && ! $isGameWhiteListed) {
            $this->returnError('sendinclip.errors.game_blocked');
        }

        // Everything is OK, submit clip and store/update clip creator
        User::updateOrCreate([
            'id' => $clipInfo->creator_id,
        ], [
            'name' => $clipInfo->creator_name,
        ]);

        $importClipAction->execute(
            $clipInfo,
            $request->user(),
            $isAnonymous,
            $tagIds
        );

        return $this->create()
            ->with('submit_ok', true)
            ->with('submit_message', __('sendinclip.flash.submitted'));
    }

    private function getUserToken(): string
    {
        return session()?->get('twitch_access_token');
    }

    /**
     * Summary of returnError
     */
    private function returnError(string $errorKey): void
    {
        throw ValidationException::withMessages(['clip_url' => __($errorKey)]);
    }
}
