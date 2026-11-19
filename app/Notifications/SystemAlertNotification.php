<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SystemAlertNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
        public string $type = 'general',      // tender_created, tender_deadline, tender_won, etc.
        public string $level = 'info',       // info, success, warning, danger
        public ?string $actionUrl = null,
        public ?string $actionText = null,
        public ?string $icon = null,
        public ?string $uniqueKey = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'level' => $this->level,
            'action_url' => $this->actionUrl,
            'action_text' => $this->actionText,
            'icon' => $this->icon,
            'unique_key' => $this->uniqueKey,
        ];
    }
}
