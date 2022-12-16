<?php

namespace App\Models;

use App\Observers\MainCategoryObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory;

/**
 * @method static where(string $string, $default_language)
 * @method static Selection()
 */
class MainCategory extends Model
{
    use HasFactory;

    protected $table = 'main_categories';
    protected $fillable = [
        'translation_lang',
        'translation_of',
        'name',
        'slug',
        'photo',
        'active',
    ];
    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        MainCategory::observe(MainCategoryObserver::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
    public function scopeDefaultCategory($query)
    {
        return $query->where('translation_of', 0);
    }
    public function scopeSelection($query)
    {
        return $query->select('id','translation_lang','translation_of','name','slug','photo','active');
    }
    public function getActive(){
        return $this->active == 1 ? 'مفعل ' : 'غير مفعل ';
    }
    public function getPhotoAttribute($val)
    {
        return ($val !== null) ? asset($val) : "";

    }
    // get all translation for categories
    public function categories(){
        return $this ->hasMany(self::class ,'translation_of');
    }
    // get all subCategories
    public function subCategories(){
        return $this ->hasMany(SubCategory::class ,'category_id');
    }
    public function vendors(){
        return $this->hasMany('App\Models\Vendor','category_id','id');
    }
}
