<?php

declare(strict_types=1);

namespace App\Notifications\Admin;

use Illuminate\Support\Str;
use Kirschbaum\Commentions\Config;
use Kirschbaum\Commentions\Notifications\UserMentionedInComment as BaseNotification;

class UserMentionedInCommentNotification extends BaseNotification
{
    public function toArray(object $notifiable): array
    {
        $url = Config::resolveCommentUrl($this->comment);
        $author = $this->comment->getAuthorName();
        $body = Str::limit(strip_tags($this->comment->getBody()), 100);

        return [
            'duration' => 'persistent',
            'format' => 'filament',

            'title' => "{$author} mentioned you",
            'body' => $body,
            'status' => 'info',
            'icon' => 'heroicon-o-chat-bubble-left-right',
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
