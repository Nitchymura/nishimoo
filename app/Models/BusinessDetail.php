<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class BusinessDetail extends Model
{
    protected $table = 'business_details';
    use SoftDeletes;
    protected $fillable = [
        'business_id',
        'business_info_id',
        'is_valid'
    ];
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function businessInfo()
    {
        return $this->belongsTo(BusinessInfo::class);
    }
}