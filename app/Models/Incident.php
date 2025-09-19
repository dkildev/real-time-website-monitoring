<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = ['monitor_id', 'state', 'down_at', 'up_at'];

    public function monitor()
    {
        return $this->belongsTo(Monitor::class);
    }
}