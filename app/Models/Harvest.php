<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Harvest extends Model
{
    use HasFactory;

    protected $fillable = 
    [
        'fingerling_id',
        'harvest_date',
        'total_harvest',
        'image_path'
    ];


    protected $casts = [
        'image_path' => 'array',
    ];

    /**
     * Once a pond is harvested, archive it (out of the active list) while keeping
     * the pond, its fingerlings, and this harvest record intact. Runs for any
     * creation path — beneficiary panel, admin panel, or seeders.
     */
    protected static function booted(): void
    {
        static::created(function (Harvest $harvest) {
            $harvest->loadMissing('fingerling.fishpond');
            $pond = $harvest->fingerling?->fishpond;

            if ($pond && $pond->harvested_at === null) {
                $pond->update(['harvested_at' => $harvest->harvest_date ?? now()]);
            }
        });
    }

    public function fingerling(): BelongsTo
    {
        return $this->belongsTo(Fingerling::class);
    }

}
