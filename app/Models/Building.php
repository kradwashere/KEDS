<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name' , 'address', 'shortcut'
    ];

    public function rooms()
    {
        return $this->hasMany('App\Models\Room', 'building_id');
    } 

    public function roomcount()
    {
        return $this->hasMany('App\Models\Room', 'building_id')->count();
    } 

    public function available()
    {
        return $this->hasMany('App\Models\Room', 'building_id')->where('is_available',1)->count();
    } 

    public function getNameAttribute($value){
        return ucwords(strtolower($value));
    }

    public function getAddressAttribute($value){
        return ucwords(strtolower($value));
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }
}
