<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PageView extends Model
{
    protected $fillable = ['page_id', 'page_type', 'views'];

    public function page()
    {
        return $this->morphTo();
    }
}
