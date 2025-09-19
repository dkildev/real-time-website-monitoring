<?php
namespace App\Services;

use App\Models\{Monitor, MonitorCheck, Incident};
use Illuminate\Support\Facades\Notification;
use App\Notifications\{MonitorDown, MonitorUp};

class IncidentService
{
    public function evaluate(Monitor $monitor, MonitorCheck $lastCheck): void
    {
        $failsNeeded = (int) config('monitor.failures_for_down', 2);
        $oksNeeded = (int) config('monitor.successes_for_up', 2);

        $recent = $monitor->checks()->latest('id')->take(max($failsNeeded, $oksNeeded))->get();

        // DOWN transition
        if ($this->isConsecutive($recent, false, $failsNeeded) && !$this->hasOpenIncident($monitor)) {
            $monitor->incidents()->create([
                'state' => 'open',
                'down_at' => now(),
            ]);

            Notification::route('mail', config('monitor.notify_email'))
                ->notify(new MonitorDown($monitor, $lastCheck));
        }

        // UP transition
        if ($this->isConsecutive($recent, true, $oksNeeded) && $open = $this->openIncident($monitor)) {
            $open->update(['state' => 'resolved', 'up_at' => now()]);

            Notification::route('mail', config('monitor.notify_email'))
                ->notify(new MonitorUp($monitor, $lastCheck));
        }
    }

    private function isConsecutive($checks, bool $desired, int $count): bool
    {
        if ($checks->count() < $count)
            return false;
        return $checks->take($count)->every(fn($c) => (bool) $c->ok === $desired);
    }

    private function hasOpenIncident(Monitor $m): bool
    {
        return $m->incidents()->where('state', 'open')->exists();
    }

    private function openIncident(Monitor $m): ?Incident
    {
        return $m->incidents()->where('state', 'open')->latest()->first();
    }
}
