<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = 'device';

    protected $fillable = [
        'nama',
        'site',
        'snslv',
        'status',
        'witel',
        'network',
        'tipe_dispenser',
        'total_dispenser',
        'dispenser_integrasi',
        'dispenser_tidak_terintegrasi',
        'lock_voltage',
        'min_voltage',
        'max_voltage',
        'device_key',
        'telegram',
    ];

    public function logSensors()
    {
        return $this->hasMany(LogSensor::class, 'device_key', 'device_key');
    }

    public function reportBroadcasts()
    {
        return $this->hasMany(ReportBroadcast::class, 'device_key', 'device_key');
    }
}
