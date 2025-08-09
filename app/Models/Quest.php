<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Quest extends Model
{
    use SoftDeletes;

    protected $table = 'quests';

    protected $dates = ['deleted_at'];
    public $timestamps = true;

    protected $fillable = [
        'title', 'user_id', 'start_date', 'end_date', 'duration',
        'introduction', 'main_image', 'is_public'
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function questLikes(){
        return $this->hasMany(QuestLike::class);
    }

    public function questComments(){
        return $this->hasMany(QuestComment::class);
    }

    public function pageViews(){
        return $this->morphMany(PageView::class, 'page');
    }

    public function isLiked(){
        if (!Auth::check()) {
            return false;
        }
        return $this->questLikes()->where('user_id', Auth::user()->id)->exists();
    }

    public function views(): MorphMany{
        return $this->morphMany(PageView::class, 'page');
    }

    public function likes(){
        return $this->hasMany(QuestLike::class);
    }

    public function comments(){
        return $this->hasMany(QuestComment::class);
    }


    public function questBodies(){
        return $this->hasMany(QuestBody::class, 'quest_id', 'id')
                    ->orderBy('day_number')
                    ->orderBy('id');
    }

}
