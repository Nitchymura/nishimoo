<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \App\Models\User $user
 * @property \App\Models\Spot $spot
 */

class SpotLike extends Model
{
    protected $table = 'spot_likes'; 

    public $timestamps = false; 
    protected $fillable = ['user_id', 'spot_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function spot(){
        return $this->belongsTo(Spot::class);
    }

    public function postRelation(){
        return $this->morphTo(); // あるいは特定のPostモデルへのbelongsTo
    }
}
