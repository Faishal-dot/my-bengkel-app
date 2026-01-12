<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'ktp',
        'phone',
        'address',
        'specialization',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}