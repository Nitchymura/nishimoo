<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpotComment extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'spot_id', 'content']; 
    protected $table = 'spot_comments';

    public function spot(){
        return $this->belongsTo(Spot::class);
    }    
 
    public function spotCommentlikes(){
        return $this->hasMany(SpotCommentLike::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function isLiked(){
        return $this->spotCommentLikes()->where('user_id', Auth::user()->id)->exists();
    }

    public function likes(){
        return $this->hasMany(SpotCommentLike::class);
    }
} 
