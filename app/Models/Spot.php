<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Spot extends Model
{
    use SoftDeletes;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function spotLikes(){
        return $this->hasMany(SpotLike::class);
    }

    public function pageViews(){
        return $this->morphMany(PageView::class, 'page');
    }
    
    public function isLiked(){
        if (!Auth::check()) {
            return false;
        }
        return $this->spotLikes()->where('user_id', Auth::user()->id)->exists();
    }

    public function views(): MorphMany{
        return $this->morphMany(PageView::class, 'page');
    }

    public function likes(){
        return $this->hasMany(SpotLike::class);
    }

    public function comments(){
        return $this->hasMany(SpotComment::class);
    }


    public function spotComments(){
        return $this->hasMany(SpotComment::class);
    }

    
}
