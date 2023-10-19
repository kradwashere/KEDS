<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Http\Resources\DefaultResource;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function store(Request $request){

        $validated = $request->validate([
            'number' => 'required',
            'floor' => 'required',
            'bed_type' => 'required',
            'room_type' => 'required',
            'building_id' => 'required',
            'price' => 'required'
        ]);

        $data = \DB::transaction(function () use ($request){
            $data = Room::create($request->all());
            return $data;
        });

        return back()->with([
            'message' => 'Successfully Added',
            'data' => new DefaultResource($data),
            'type' => 'bxs-check-circle'
        ]); 
    }

    public function update(Request $request)
    {   
        $data = \DB::transaction(function () use ($request){
          
            $data = Room::findOrFail($request->id);
            $data->update($request->all());
            return $data;
        });
        
        return back()->with([
            'message' => 'Room updated successfully. Thanks',
            'data' => new DefaultResource($data),
            'type' => 'bxs-check-circle'
        ]); 
    }
}
