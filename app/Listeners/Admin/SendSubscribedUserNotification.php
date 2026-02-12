<?php

declare(strict_types=1);

namespace App\Listeners\Admin;

use App\Models\User;
use App\Notifications\Admin\NewSubscribedCommentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Kirschbaum\Commentions\Events\UserIsSubscribedToCommentableEvent;

class SendSubscribedUserNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UserIsSubscribedToCommentableEvent $event): void
    {
        /** @var User $user */
        $user = $event->user;

        $user->notify(
            new NewSubscribedCommentNotification($event->comment)
        );
    }
}
