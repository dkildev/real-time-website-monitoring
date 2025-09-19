<?php

namespace App\Notifications;

use App\Models\{Monitor, MonitorCheck};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\{Notification, Messages\MailMessage};

class MonitorDown extends Notification implements ShouldQueue
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
            ->subject("[ALERT] {$this->monitor->name} is DOWN")
            ->line("URL: {$this->monitor->url}")
            ->line("Status: " . ($this->check->status_code ?? 'No response'))
            ->line("Error: " . ($this->check->error ?? 'n/a'))
            ->line("Checked at: " . now()->setTimezone(config('app.timezone'))->toDateTimeString());
    }
}
