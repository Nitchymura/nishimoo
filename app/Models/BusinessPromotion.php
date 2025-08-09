<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessPromotion extends Model
{
    use SoftDeletes;
    protected $table = 'business_promotions';
    
    //promotion belongs to one business
    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
