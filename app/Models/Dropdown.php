<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dropdown extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    protected $fillable = ['name','type'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    public function beds()
    {
        return $this->hasMany('App\Models\Room', 'bed_type');
    } 

    public function rooms()
    {
        return $this->hasMany('App\Models\Room', 'room_type');
    } 
}
