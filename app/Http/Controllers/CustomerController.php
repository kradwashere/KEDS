<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Resources\CustomerResource;
use App\Http\Requests\CustomerRequest;

class CustomerController extends Controller
{
    public function index(Request $request){
        $type = $request->type;
        switch($type){
            case 'lists':
                return $this->lists($request);
            break;
            default : 
            return inertia('Customers/Index');
        }
    }

    public function store(CustomerRequest $request){
        $data = \DB::transaction(function () use ($request){
            if($request->editable){
                $data = Customer::where('id',$request->id)->first();
                $data->update($request->except('editable'));
                return $data;
            }else{
               
                $data = Customer::create($request->all());
                if($data){
                    if($request['identification'])
                    {
                        $file = $request['identification'];   
                        $file_name = $data->name.'-'.$data->id.'.'.$file[0]->getClientOriginalExtension();
                        $file_path = $file[0]->storeAs('uploads/'.$data->id.'/', $file_name, 'public');
        
                        $fileinfo = pathinfo($file_name, PATHINFO_FILENAME);

                        $wee = Customer::findOrFail($data->id);
                        $wee->identification = $file_name;
                        $wee->save();
                    }
                }
                $data = Customer::where('id',$data->id)->first();
                return $data;
            }
        });
        
        return back()->with([
            'message' => 'Customer created successfully. Thanks',
            'data' => new CustomerResource($data),
            'type' => 'bxs-check-circle'
        ]); 
    }

    public function lists($request){
        $keyword = $request->keyword;
        $data = CustomerResource::collection(
            Customer::when($keyword, function ($query, $keyword) {
                $query->where('name','LIKE','%'.$keyword.'%');
            })
            ->orderBy('name','ASC')
            ->paginate($request->counts)
            ->withQueryString()
        );
        return $data;
    }
}


