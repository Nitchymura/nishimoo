<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestComment extends Model{

    use SoftDeletes;
    protected $table = 'quest_comments';

    public function quest(){
        return $this->belongsTo(Quest::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function questCommentlikes(){
        return $this->hasMany(QuestCommentLike::class);
    }

    public function isLiked(){
        return $this->questCommentLikes()->where('user_id', Auth::user()->id)->exists();
    }
}
