<?php

namespace App\Notifications;

use App\Models\{Monitor, MonitorCheck};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\{Notification, Messages\MailMessage};

class MonitorUp extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Monitor $monitor, public MonitorCheck $check)
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("[RESOLVED] {$this->monitor->name} is back UP")
            ->line("URL: {$this->monitor->url}")
            ->line("Status: " . ($this->check->status_code ?? 'n/a'))
            ->line("Latency: " . ($this->check->latency_ms ? round($this->check->latency_ms) . " ms" : 'n/a'))
            ->line("Checked at: " . now()->setTimezone(config('app.timezone'))->toDateTimeString());
    }
}
