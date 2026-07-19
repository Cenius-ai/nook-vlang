<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VoteReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $votableType,
        public string $votableTitle,
        public string $votableSlug,
        public int $value,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $direction = $this->value > 0 ? 'upvoted' : 'downvoted';
        $type = $this->votableType === 'App\\Models\\Question' ? 'question' : 'answer';
        return [
            'message' => "Someone {$direction} your {$type} \"{$this->votableTitle}\"",
            'question_slug' => $this->votableSlug,
        ];
    }
}
