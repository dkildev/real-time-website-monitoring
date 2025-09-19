<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitorCheck extends Model
{
    public $timestamps = false;
    protected $fillable = ['monitor_id', 'ok', 'status_code', 'latency_ms', 'error', 'checked_at'];

    public function monitor()
    {
        return $this->belongsTo(Monitor::class);
    }
}

