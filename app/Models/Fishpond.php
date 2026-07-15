<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fishpond extends Model
{
    /** @use HasFactory<\Database\Factories\FishpondFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'size',
        'location',
        'picture',
        'harvested_at',
    ];

    protected $casts = [
        'picture' => 'array',
        'harvested_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Ponds that have not been harvested yet (still in an active cycle). */
    public function scopeActive($query)
    {
        return $query->whereNull('harvested_at');
    }

    public function getNameWithOwnerAttribute()
    {
        return $this->name . ' - ' . $this->user->name; 
    }
}
