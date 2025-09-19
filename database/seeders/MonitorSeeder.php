<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Monitor;

class MonitorSeeder extends Seeder
{
    public function run(): void
    {
        Monitor::updateOrCreate(
            ['url' => 'https://example.org'],
            ['name' => 'Example.org', 'interval_seconds' => 60, 'timeout_seconds' => 8, 'expect' => 'Example Domain', 'enabled' => true]
        );

        Monitor::updateOrCreate(
            ['url' => 'https://httpstat.us/500'],
            ['name' => 'Deliberate 500', 'interval_seconds' => 60, 'timeout_seconds' => 5, 'enabled' => true]
        );
    }
}
