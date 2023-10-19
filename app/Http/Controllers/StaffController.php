<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\DefaultResource;

class StaffController extends Controller
{
    public function index(Request $request){
        $user = \Auth::user()->role;
        if($request->search == 'lists'){
            $data = DefaultResource::collection(
                User::query()
                ->when($request->keyword, function ($query, $keyword) {
                    $query->where('name','LIKE', "%{$keyword}%");
                })
                ->where('role','!=','Client')
                ->get()
            );
            return $data;
        }else if($user == 'Administrator'){
            return inertia('Staffs/Index');
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
            'name' => 'required',
            'email' => 'required|unique:users|max:255',
            'mobile' => 'required',
        ]);
        

        $data = \DB::transaction(function () use ($request){
            $data = User::create(array_merge($request->all(), ['password' => bcrypt('123456789')]));
            return $data;
        });

        return back()->with([
            'message' => 'Staff Added',
            'data' => new DefaultResource($data),
            'type' => 'bxs-check-circle'
        ]); 
    }

    public function update(Request $request)
    {   
        $data = \DB::transaction(function () use ($request){
          
            $data = User::findOrFail($request->id);
            $data->update($request->except('editable'));
            return $data;
        });
        
        return back()->with([
            'message' => 'Staff updated successfully. Thanks',
            'data' => new DefaultResource($data),
            'type' => 'bxs-check-circle'
        ]); 
    }
}
