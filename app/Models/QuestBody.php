<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Quest;

class QuestBody extends Model
{
    protected $table = 'quest_bodies';
    protected $fillable = ['spot_id', 'business_id', 'quest_id'];

    // QuestBody belongs to Quest
    public function quest(): BelongsTo{
        return $this->belongsTo(Quest::class, 'quest_id');
    }

    // QuestBody belongs to Spot
    public function spot(): BelongsTo{
        return $this->belongsTo(Spot::class, 'spot_id');
    }

    // QuestBody belongs to Business
    public function business(): BelongsTo{
        return $this->belongsTo(Business::class, 'business_id');
    }
}

