<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportBroadcast extends Model
{
    use HasFactory;

    protected $table = 'report_broadcast';

    protected $fillable = [
        'device_key',
        'status_voltage',
        'broadcast_at',
        'broadcast_total',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_key', 'device_key');
    }

    public $timestamps = false;   
}
