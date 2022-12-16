<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'vendors';
    protected $fillable = [
        'name',
        'mobile',
        'address',
        'email',
        'category_id',
        'active',
        'logo',
        'password',
        'latitude',
        'longitude',
    ];
    protected $hidden = [
        'category_id',
        'password',
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\MainCategory', 'category_id', 'id');
    }


    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function getLogoAttribute($val)
    {
        return ($val !== null) ? asset($val) : "";

    }

    public function setPasswordAttribute($password)
    {
        if (!empty($password)) {
            $this->attributes['password'] = bcrypt($password);
        }

    }

    public function scopeSelection($query)
    {
        return $query->select('id', 'name', 'address','longitude','latitude', 'mobile', 'active', 'category_id', 'logo');
    }

    public function getActive()
    {
        return $this->active == 1 ? 'مفعل ' : 'غير مفعل ';
    }
}
