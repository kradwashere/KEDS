<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['number','floor','price','remarks','is_available','is_active','room_type','bed_type','building_id'];

    public function building()
    {
        return $this->belongsTo('App\Models\Building', 'building_id', 'id');
    }

    public function roomtype()
    {
        return $this->belongsTo('App\Models\Dropdown', 'room_type', 'id');
    }

    public function bedtype()
    {
        return $this->belongsTo('App\Models\Dropdown', 'bed_type', 'id');
    }

    public function bookings()
    {
        return $this->hasMany('App\Models\Booking', 'room_id');
    } 
}
