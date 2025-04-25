<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    use HasFactory;

    protected $table = 'subdistrict';

    protected $primaryKey = 'id';

    protected $fillable = [
        'subdistrict_name',
        'district_id',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function postalCodes()
    {
        return $this->hasMany(PostalCode::class);
    }

    public function offices()
    {
        return $this->hasMany(Office::class);
    }
}