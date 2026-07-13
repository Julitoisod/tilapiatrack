<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;


    protected $fillable = [
        'chat_id',
        'user_id',
        'message',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        // 'message' is a plain text column. Casting it to 'array' json-encoded
        // outgoing strings and made older plain-text rows decode to null (blank
        // bubbles). Keep it a string.
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
