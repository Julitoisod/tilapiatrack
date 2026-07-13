<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedingProgram extends Model
{
    /** @use HasFactory<\Database\Factories\FeedingProgramFactory> */
    use HasFactory;

    protected $fillable = [
        'feed_id',
        'name',
        // Fingerling Stage
        'fingerling_age_range',
        'fingerling_feeding_frequency',
        'fingerling_feed_time',
        'fingerling_fish_amount',
        'fingerling_protein_content',
        'fingerling_weight_range',
        // Juvenile Stage
        'juvenile_age_range',
        'juvenile_feeding_frequency',
        'juvenile_feed_time',
        'juvenile_fish_amount',
        'juvenile_protein_content',
        'juvenile_weight_range',
        // Sub-Adult Stage
        'subadult_age_range',
        'subadult_feeding_frequency',
        'subadult_feed_time',
        'subadult_fish_amount',
        'subadult_protein_content',
        'subadult_weight_range',
        // Adult Stage
        'adult_age_range',
        'adult_feeding_frequency',
        'adult_feed_time',
        'adult_fish_amount',
        'adult_protein_content',
        'adult_weight_range',
    ];

    public function feed(): BelongsTo
    {
        return $this->belongsTo(Feed::class);
    }

    protected $casts = [
        'fingerling_feed_time' => 'array',
        'juvenile_feed_time' => 'array',
        'subadult_feed_time' => 'array',
        'adult_feed_time' => 'array',
        'fingerling_fish_amount' => 'decimal:2',
        'juvenile_fish_amount' => 'decimal:2',
        'subadult_fish_amount' => 'decimal:2',
        'adult_fish_amount' => 'decimal:2',
        'fingerling_protein_content' => 'decimal:2',
        'juvenile_protein_content' => 'decimal:2',
        'subadult_protein_content' => 'decimal:2',
        'adult_protein_content' => 'decimal:2',
    ];

    public function setFingerlingFeedTimeAttribute($value)
    {
        $this->setFormattedFeedTime('fingerling_feed_time', $value);
    }

    public function setJuvenileFeedTimeAttribute($value)
    {
        $this->setFormattedFeedTime('juvenile_feed_time', $value);
    }

    public function setSubadultFeedTimeAttribute($value)
    {
        $this->setFormattedFeedTime('subadult_feed_time', $value);
    }

    public function setAdultFeedTimeAttribute($value)
    {
        $this->setFormattedFeedTime('adult_feed_time', $value);
    }

    private function setFormattedFeedTime($attribute, $value)
    {
        if (is_array($value)) {
            $formattedTimes = array_map(function ($time) {
                return substr($time, 0, 5) . ':01';
            }, $value);
            $this->attributes[$attribute] = json_encode($formattedTimes);
        } else {
            $this->attributes[$attribute] = json_encode([substr($value, 0, 5) . ':01']);
        }
    }
}