<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'province';

    protected $primaryKey = 'id';

    protected $fillable = [
        'province_name',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function offices()
    {
        return $this->hasMany(Office::class);
    }
}