<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewAnswerNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $questionTitle,
        public string $questionSlug,
        public string $answererName,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "{$this->answererName} answered your question \"{$this->questionTitle}\"",
            'question_slug' => $this->questionSlug,
        ];
    }
}
