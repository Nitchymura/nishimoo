<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Detail extends Model
{
    use SoftDeletes;

    protected $fillable = ['category', 'name', 'business_detail_id'];
    public function businessDetail(){
        return $this->belongsTo(BusinessDetail::class);
    }
}
