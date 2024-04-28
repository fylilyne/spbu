<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSensor extends Model
{
    use HasFactory;

    protected $table = 'log_sensor';

    protected $fillable = [
        'voltage',
        'timestamp',
        'last_notification_timestamp',
        'status',
        'device_key',
        'status_led',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'last_notification_timestamp' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_key', 'device_key');
    }

    public $timestamps = false;   
}
