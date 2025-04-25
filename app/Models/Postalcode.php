<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostalCode extends Model
{
    use HasFactory;

    protected $table = 'poscode';

    protected $primaryKey = 'id';

    protected $fillable = [
        'poscode',
        'subdistrict_id',
    ];

    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class);
    }

    public function offices()
    {
        return $this->hasMany(Office::class);
    }
}