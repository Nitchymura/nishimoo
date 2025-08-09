<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessLike extends Model
{
    protected $table = 'business_likes'; 

    public $timestamps = false; 
    protected $fillable = ['user_id', 'business_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function postRelation(){
        return $this->morphTo(); // あるいは特定のPostモデルへのbelongsTo
    }
}
