<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageViewLog extends Model
{
    protected $fillable = ['page_id', 'page_type', 'ip_address'];
}
