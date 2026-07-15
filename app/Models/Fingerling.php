<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fingerling extends Model
{
    /** @use HasFactory<\Database\Factories\FingerlingFactory> */
    use HasFactory;

    protected $fillable = [
        'fishpond_id',
        'species',
        'date_deployed',
        'quantity',
        'weight',
        'feed_amount'

    ];

    public function fishpond(): BelongsTo
    {
        return $this->belongsTo(Fishpond::class);
    }

    /**
     * Days since this batch was deployed. Drives the growth stage and feeding
     * progress; 0 when undeployed or the deploy date is in the future.
     */
    public function getAgeInDaysAttribute(): int
    {
        if (!$this->date_deployed) {
            return 0;
        }

        return (int) max(0, Carbon::parse($this->date_deployed)->startOfDay()->diffInDays(now()->startOfDay(), false));
    }
}
