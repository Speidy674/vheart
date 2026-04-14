<?php

declare(strict_types=1);

namespace App\Filament\AdminPanel\Resources\Users\Actions;

use App\Enums\Filament\LucideIcon;
use App\Models\Broadcaster\Broadcaster;
use App\Models\User;
use App\Services\Twitch\Data\UserDto;
use App\Services\Twitch\TwitchService;
use Closure;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class UpdateUserAction extends Action
{
    protected ?Closure $userResolver = null;

    public function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Update User')
            ->authorize('update')
            ->translateLabel()
            ->icon(LucideIcon::RefreshCcw)
            ->requiresConfirmation()
            ->action(function (Model $record, array $data, TwitchService $twitchService): void {
                $target = $this->evaluate($this->userResolver ?? $record);

                /** @var UserDto|null $userDto */
                $userDto = array_first(
                    $twitchService->asSessionUser()->getUsers(['id' => [$target->id]])
                );

                if (! $userDto) {
                    Notification::make()
                        ->title('Could not update user')
                        ->body('We could not fetch the user from Twitch, try again later.')
                        ->warning()
                        ->send();

                    return;
                }

                match (true) {
                    $target instanceof Broadcaster => $target->user->update($userDto->toModel()),
                    $target instanceof User => $target->update($userDto->toModel()),
                    default => throw new InvalidArgumentException('Invalid model for UpdateUserAction, only allowed are User or Broadcaster, we got '.get_class($target)),
                };
            })
            ->successNotificationTitle('User has been refreshed, avatar may be cached.');
    }

    public static function getDefaultName(): ?string
    {
        return 'updateUser';
    }

    public function resolveUserUsing(Closure $resolver): static
    {
        $this->userResolver = $resolver;

        return $this;
    }
}
