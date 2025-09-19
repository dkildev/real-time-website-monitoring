<?php
namespace App\Jobs;

use App\Models\{Monitor, MonitorCheck};
use App\Services\IncidentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class RunMonitorCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $monitorId)
    {
    }

    public function handle(IncidentService $incidentService): void
    {
        $monitor = Monitor::find($this->monitorId);
        if (!$monitor || !$monitor->enabled)
            return;

        $start = microtime(true);

        try {
            $resp = Http::timeout($monitor->timeout_seconds)
                ->withHeaders(['User-Agent' => 'UptimeProbe/1.0'])
                ->get($monitor->url);

            $latencyMs = (microtime(true) - $start) * 1000;
            $ok = $resp->successful()
                && (!$monitor->expect || str_contains($resp->body(), $monitor->expect));

            $check = $monitor->checks()->create([
                'ok' => $ok,
                'status_code' => $resp->status(),
                'latency_ms' => round($latencyMs, 1),
                'checked_at' => now(),
            ]);
        } catch (\Throwable $e) {
            $check = $monitor->checks()->create([
                'ok' => false,
                'status_code' => null,
                'latency_ms' => null,
                'error' => mb_strimwidth($e->getMessage(), 0, 2000),
                'checked_at' => now(),
            ]);
        }

        $incidentService->evaluate($monitor, $check);
    }
}
