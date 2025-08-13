<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'img',
        'location_id',
        'type',
        'price_per_hour',
        'description',
    ];

    // Relasi ke Location
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
