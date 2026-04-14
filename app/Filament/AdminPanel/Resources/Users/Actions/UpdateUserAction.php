<?php

declare(strict_types=1);

namespace App\Filament\AdminPanel\Resources\Users\Actions;

use App\Enums\Filament\LucideIcon;
use App\Models\Broadcaster\Broadcaster;
use App\Models\User;
use App\Services\Twitch\Data\UserDto;
use App\Services\Twitch\TwitchService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class UpdateUserAction extends Action
{
    public function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Update User')
            ->translateLabel()
            ->icon(LucideIcon::RefreshCcw)
            ->requiresConfirmation()
            ->action(function (User|Broadcaster $record, array $data, TwitchService $twitchService): void {
                /** @var UserDto $userDto */
                $userDto = array_first($twitchService->asSessionUser()->getUsers(['id' => [$record->id]]));

                if (! $userDto) {
                    Notification::make()
                        ->title('Could not update user')
                        ->body('We could not fetch the user from Twitch, try again later.')
                        ->warning()
                        ->send();

                    return;
                }

                if ($record instanceof Broadcaster) {
                    $record->user->update($userDto->toModel());
                } else {
                    $record->update($userDto->toModel());
                }
            })
            ->successNotificationTitle('User has been refreshed, avatar may be cached.');
    }

    public static function getDefaultName(): ?string
    {
        return 'updateUser';
    }
}
