<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BusinessInfo extends Model
{
    protected $table = 'business_info';
    protected $fillable = ['name', 'business_info_category_id'];
    public function businessDetails()
    {
        return $this->hasMany(BusinessDetail::class);
    }
    public function category()
    {
        return $this->belongsTo(BusinessInfoCategory::class, 'business_info_category_id');
    }
}