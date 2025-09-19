<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    protected $fillable = [
        'name',
        'url',
        'interval_seconds',
        'timeout_seconds',
        'expect',
        'enabled'
    ];

    public function checks()
    {
        return $this->hasMany(MonitorCheck::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function lastCheck(): ?MonitorCheck
    {
        return $this->checks()->latest('id')->first();
    }
}
