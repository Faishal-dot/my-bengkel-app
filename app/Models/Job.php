<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'vehicle',
        'problem',
        'status',
        'mechanic_id',
    ];

    public function mechanic()
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }
}