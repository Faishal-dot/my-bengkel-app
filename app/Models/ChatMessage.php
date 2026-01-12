<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = ['booking_id', 'user_id', 'message', 'is_read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
