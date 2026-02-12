<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\User;
use App\Services\Twitch\Data\ClipDto;
use App\Services\Twitch\Exceptions\TwitchApiException;
use App\Services\Twitch\TwitchEndpoints;
use App\Services\Twitch\TwitchService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SubmitClipRequest extends FormRequest
{
    public ?ClipDto $clipInfo = null;

    public ?string $clipId = null;

    public function __construct(
        protected TwitchService $twitchService
    )
    {
        parent::__construct();

        $this->twitchService->onUserTokenRefresh(function ($token) {
            session()?->put('twitch_access_token', $token);
        });
    }

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'clip_url' => ['required', 'string', 'url'],
            'tags' => ['required', 'array', 'min:1', 'max:3'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'is_anonymous' => ['sometimes', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'parsed_clip_id.required' => __('sendinclip.errors.clip_not_found'),
            'parsed_clip_id.unique' => __('sendinclip.errors.clip_already_known'),
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }
                $this->clipId = $this->twitchService->parseClipId($this->input('clip_url'));

                if (! $this->clipId) {
                    $validator->errors()->add('clip_url', __('sendinclip.errors.clip_not_found'));

                    return;
                }

                $user = $this->user();

                $this->clipInfo = $this->twitchService
                    ->asUser($user, session()?->get('twitch_access_token'))
                    ->getClipByID($this->clipId);

                if (! $this->clipInfo) {
                    $validator->errors()->add('clip_url', __('sendinclip.errors.clip_not_found'));

                    return;
                }

                // Check if the Category is Site-Banned
                $isCategoryBanned = Category::query()
                    ->where('is_banned', true)
                    ->where('id', $this->clipInfo->game_id)
                    ->exists();

                if ($isCategoryBanned) {
                    $validator->errors()->add('clip_url', __('sendinclip.errors.category_blocked'));

                    return;
                }

                // Check if the Broadcaster is even registered (block if not)
                $broadcasterUser = User::query()
                    ->where('id', $this->clipInfo->broadcaster_id)
                    ->where('users.clip_permission', true)
                    ->first();

                if ($broadcasterUser === null) {
                    $validator->errors()->add('clip_url', __('sendinclip.errors.broadcaster_not_allowed'));

                    return;
                }

                // Check if user is blacklisted by Broadcaster
                $isUserBlackedListed = $broadcasterUser
                    ->broadcasterUserFilter()
                    ->where('filter_id', $user->id)
                    ->where('allowed', false)
                    ->exists();

                if ($isUserBlackedListed) {
                    $validator->errors()->add('clip_url', __('sendinclip.errors.user_not_allowed_for_broadcaster'));

                    return;
                }

                // Check Broadcaster Rules
                $broadcasterRules = $broadcasterUser->rules ?? [];
                $isUserAllowed = empty($broadcasterRules) || $this->clipInfo->broadcaster_id === $user->id;

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
                    $isUserAllowed = $this->twitchService
                        ->asUser($user, session()?->get('twitch_access_token'))
                        ->isModeratorFor($broadcasterUser);
                }

                // Check if Broadcaster has enabled VIP Allow-list and if the User is on it
                if (! $isUserAllowed && in_array('userAllowVips', $broadcasterRules, true)) {
                    try {
                        $vipInfos = $this->twitchService->asUser($broadcasterUser)->onUserTokenRefresh()->get(TwitchEndpoints::GetVIPs, [
                            'user_id' => $user->id,
                            'broadcaster_id' => $this->clipInfo->broadcaster_id,
                        ]);
                        $isUserAllowed = ! empty($vipInfos['data']);
                    } catch (TwitchApiException $th) {
                        report($th);
                    }
                }

                // If user was not allowed by any of the previous checks, deny
                if (! $isUserAllowed) {
                    $validator->errors()->add('clip_url', __('sendinclip.errors.user_not_allowed_for_broadcaster'));

                    return;
                }

                // Check if Broadcaster has banned the Category
                $isGameBlackListed = $broadcasterUser
                    ->broadcasterCategoryFilter()
                    ->where('filter_id', $this->clipInfo->game_id)
                    ->where('allowed', false)
                    ->exists();

                if ($isGameBlackListed) {
                    $validator->errors()->add('clip_url', __('sendinclip.errors.category_blocked'));

                    return;
                }

                // Check if Broadcaster has enabled Category Whitelist (>0 entries)
                $hasOneGameWhiteListed = $broadcasterUser
                    ->broadcasterCategoryFilter()
                    ->where('allowed', true)
                    ->exists();

                // If whitelist has entries, check if category is whitelisted
                if ($hasOneGameWhiteListed) {
                    $isGameWhiteListed = $broadcasterUser
                        ->broadcasterCategoryFilter()
                        ->where('filter_id', $this->clipInfo->game_id)
                        ->where('allowed', true)
                        ->exists();

                    if (! $isGameWhiteListed) {
                        $validator->errors()->add('clip_url', __('sendinclip.errors.category_blocked'));

                        return;
                    }
                }

                // Everything is OK
            },
        ];
    }
}
