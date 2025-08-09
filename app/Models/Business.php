<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Business extends Model
{
    use SoftDeletes;
    protected $fillable = ['name'];
   
    //business belongs to one user
    public function user(){
        return $this->belongsTo(User::class);
    }

    //business has many promotions
    public function businessPromotions(){
        return $this->hasMany(BusinessPromotion::class);

    }

    public function businessComment(){
        return $this->hasMany(BusinessComment::class);
    }

    public function photos(){
        return $this->hasMany(Photo::class, 'business_id');
    }

    public function photoPriorityOne(){
        return $this->hasOne(Photo::class)->where('priority', 1);
    }

    public function topPhoto(){
        return $this->hasOne(Photo::class)->orderBy('priority', 'asc');
    }

    public function businessLikes(){
        return $this->hasMany(BusinessLike::class);
    }

    public function pageViews(){
        return $this->morphMany(PageView::class, 'page');
    }

    public function isLiked(){
        if (!Auth::check()) {
            return false;
        }
        return $this->businessLikes()->where('user_id', Auth::user()->id)->exists();
    }

    public function views(): MorphMany{
        return $this->morphMany(PageView::class, 'page');
    }

    public function businessComments(){
        return $this->hasMany(BusinessComment::class);
    }

    public function comments(){
        return $this->hasMany(BusinessComment::class);
    }

    public function businessDetails(){

        return $this->hasMany(BusinessDetail::class);
    }

    public function hasBusinessInfo($infoName){
        return $this->businessDetails
            ->load('businessInfo')
            ->pluck('businessInfo')
            ->filter()
            ->contains('name', $infoName);
    }

    public function hasBusinessInfoId($id){
        return $this->businessDetails->where('business_info_id', $id)->where('is_valid', 1)->isNotEmpty();
    }


    public function businessHours(){
        return $this->hasMany(BusinessHour::class);
    }

    public function likes(){
        return $this->hasMany(BusinessLike::class);
    }
}
