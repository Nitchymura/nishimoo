<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpotCommentLike extends Model
{
    protected $fillable = ['user_id', 'spot_comment_id']; 
    protected $table = 'spot_comment_likes';

    public $timestamps = false;

    public function spotComment(){
        return $this->belongsTo(SpotComment::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

} 