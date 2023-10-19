<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Resources\DefaultResource;

class ServiceController extends Controller
{
    public function index(Request $request){
        $user = \Auth::user()->role;
        if($user == 'Administrator'){
            return inertia('Services/Index');
        }else{
            return back()->with([
                'message' => 'Not Authorized',
                'data' => 'not',
                'type' => 'bxs-x-circle'
            ]); 
        }
    }

    public function store(Request $request){

        $validated = $request->validate([
            'name' => 'required|unique:services|max:255',
            'amount' => 'required',
        ]);
        
        $data = \DB::transaction(function () use ($request){
            $data = Service::create($request->all());
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
          
            $data = Service::findOrFail($request->id);
            $data->update($request->except('editable'));
            return $data;
        });
        
        return back()->with([
            'message' => 'Product updated successfully. Thanks',
            'data' => new DefaultResource($data),
            'type' => 'bxs-check-circle'
        ]); 
    }
}
