<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestLike extends Model
{
    protected $table = 'quest_likes'; 

    public $timestamps = false; 
    protected $fillable = ['user_id', 'quest_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function quest(){
        return $this->belongsTo(Quest::class);
    }

    public function postRelation(){
        return $this->morphTo(); // あるいは特定のPostモデルへのbelongsTo
    }
}
