<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessHour extends Model
{
    protected $table = 'business_hours';
    use SoftDeletes;
    
    protected $fillable = ['day_of_week','business_id','opening_time', 'closing_time', 'break_start', 'break_end','is_closed','notice'];
    public function business(){
        return $this->belongsTo(Business::class);
    }
}
