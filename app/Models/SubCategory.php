<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MainCategory;

class SubCategory extends Model
{
    use HasFactory;
    protected $table = 'sub_categories';
    protected $fillable = [
        'translation_lang',
        'translation_of',
        'name',
        'slug',
        'photo',
        'active',
        'parent_id',
        'category_id',
    ];

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
        return $query->select('id', 'translation_lang', 'parent_id','translation_of', 'name', 'slug', 'photo', 'active');
    }

    public function getActive()
    {
        return $this->active == 1 ? 'مفعل ' : 'غير مفعل ';
    }

    public function getPhotoAttribute($val)
    {
        return ($val !== null) ? asset($val) : "";

    }
    public function MainCategory(){
        return $this->belongsTo(MainCategory::class,'category_id');
    }
}
