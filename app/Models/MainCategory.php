<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function scopeActive($query)
    {
        return $query->where('active', 1);
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
        return ($val !== null) ? asset('assets/' . $val) : "";

    }
}
