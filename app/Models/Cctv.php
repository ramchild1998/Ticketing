<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cctv extends Model
{
    use HasFactory;

    protected $table = 'cctv';

    protected $fillable = [
        'machine_id',
        'location_id',
        'area_id',
        'status_cctv',
        'merk_dvr',
        'sn_dvr',
        'kunci_ruangan',
        'sampling_dvr',
        'tanggal_pengerjaan',
        'note',
        'pkt_atm',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'status' => 'boolean',
        'tanggal_pengerjaan' => 'datetime'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
