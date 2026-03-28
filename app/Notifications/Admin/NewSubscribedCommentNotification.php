<?php

declare(strict_types=1);

namespace App\Notifications\Admin;

use App\Enums\Filament\LucideIcon;
use Filament\Support\Enums\IconSize;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use Kirschbaum\Commentions\Comment;
use Kirschbaum\Commentions\Config;

class NewSubscribedCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Comment $comment,
    ) {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $url = Config::resolveCommentUrl($this->comment);
        $author = $this->comment->getAuthorName();
        $body = Str::limit(strip_tags($this->comment->getBody()), 100);

        return [
            'duration' => 'persistent',
            'format' => 'filament',

            'title' => "{$author} has commented",
            'body' => $body,
            'status' => 'info',
            'icon' => LucideIcon::MessagesSquare->getIconForSize(IconSize::Medium),

            'actions' => $url ? [
                [
                    'name' => 'view',
                    'label' => 'View',
                    'url' => $url,
                    'color' => 'primary',
                ],
            ] : null,
        ];
    }
}
