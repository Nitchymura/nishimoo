<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BusinessCommentLike extends Model
{
    protected $table = 'business_comment_likes'; //table name is not in plural form, so note the different table name

    public $timestamps = false; //do not auto-save timestamps
    protected $fillable = ['user_id', 'business_comment_id']; //for create() 

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function businessComment(){
        return $this->belongsTo(BusinessComment::class);
    }


}
