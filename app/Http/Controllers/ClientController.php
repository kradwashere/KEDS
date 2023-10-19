<?php

namespace App\Http\Controllers;

use App\models\User;
use Illuminate\Http\Request;
use App\Http\Resources\DefaultResource;

class ClientController extends Controller
{
    public function index(Request $request){
        $user = \Auth::user()->role;
        if($request->search == 'lists'){
            $data = DefaultResource::collection(
                User::query()
                ->when($request->keyword, function ($query, $keyword) {
                    $query->where('name','LIKE', "%{$keyword}%");
                })
                ->where('role','Client')
                ->get()
            );
            return $data;
        }else if($request->search){
            $data = DefaultResource::collection(
                User::query()
                ->when($request->keyword, function ($query, $keyword) {
                    $query->where('name','LIKE', "%{$keyword}%");
                })
                ->get()
            );
            return $data;
        }else if($user == 'Administrator' || $user == 'Receptionist'){
            return inertia('Clients/Index');
        }else{
            return back()->with([
                'message' => 'Not Authorized',
                'data' => 'not',
                'type' => 'bxs-x-circle'
            ]); 
        }
        // if($request->search){
        //     $data = DefaultResource::collection(
        //         User::query()
        //         ->when($request->keyword, function ($query, $keyword) {
        //             $query->where('name','LIKE', "%{$keyword}%");
        //         })
        //         ->get()
        //     );
        //     return $data;
        // }else{
        //     return inertia('Clients/Index');
        // }
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
            'message' => 'Client Added',
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
            'message' => 'Client updated successfully. Thanks',
            'data' => new DefaultResource($data),
            'type' => 'bxs-check-circle'
        ]); 
    }
}
