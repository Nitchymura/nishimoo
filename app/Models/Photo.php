<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'business_photos';
    
    protected $fillable = ['business_id', 'image', 'priority'];
    
    public function business(){
        return $this->belongsTo(Business::class);
    }
}
 