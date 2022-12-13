<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static select()
 * @method static Active()
 */
class Language extends Model
{
    use HasFactory;

    protected $table = 'languages';
    protected $fillable = [
        'abbr',
        'local',
        'name',
        'direction',
        'active',
    ];
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
    public function scopeSelection($query)
    {
        return $query->select(  'id','abbr',  'name', 'direction', 'active');
    }
    public function getActive(){
        return $this->active == 1 ? 'مفعل ' : 'غير مفعل ';
    }
}
