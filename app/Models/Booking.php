<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_id',
        'user_id',
        'start_time',
        'end_time',
        'status',
    ];

    // Relasi ke field
    public function field()
    {
        return $this->belongsTo(Field::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
