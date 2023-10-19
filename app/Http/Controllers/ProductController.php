<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\DefaultResource;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index(Request $request){
        $user = \Auth::user()->role;
           if($request->search){
            $data = ProductResource::collection(
                Product::query()
                ->with('brand','part')
                ->when($request->keyword, function ($query, $keyword) {
                    $query->where('name','LIKE', "%{$keyword}%")
                    ->orWhereHas('brand',function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', "%{$keyword}%");
                    })->orWhereHas('part',function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', "%{$keyword}%");
                    });
                })
                ->get()
            );
            return $data;
        }
        if($user == 'Administrator'){
            return inertia('Products/Index');
        }else{
            return back()->with([
                'message' => 'Not Authorized',
                'data' => 'not',
                'type' => 'bxs-x-circle'
            ]); 
        }
        // if($request->search){
        //     $data = ProductResource::collection(
        //         Product::query()
        //         ->with('brand','part')
        //         ->when($request->keyword, function ($query, $keyword) {
        //             $query->where('name','LIKE', "%{$keyword}%")
        //             ->orWhereHas('brand',function ($query) use ($keyword) {
        //                 $query->where('name', 'LIKE', "%{$keyword}%");
        //             })->orWhereHas('part',function ($query) use ($keyword) {
        //                 $query->where('name', 'LIKE', "%{$keyword}%");
        //             });
        //         })
        //         ->get()
        //     );
        //     return $data;
        // }else{
        //     return inertia('Products/Index');
        // }
    }

    public function store(Request $request){

        $validated = $request->validate([
            'name' => 'required|unique:products|max:255',
            'part_id' => 'required',
            'amount' => 'required',
            'brand_id' => 'required',
            'description' => 'required',
        ]);
        
        $data = \DB::transaction(function () use ($request){
            $data = Product::create($request->all());
            return $data;
        });

        return back()->with([
            'message' => 'Successfully Added',
            'data' => new ProductResource($data),
            'type' => 'bxs-check-circle'
        ]); 
    }

    public function update(Request $request)
    {   
        $data = \DB::transaction(function () use ($request){
          
            $data = Product::findOrFail($request->id);
            $data->update($request->except('editable'));
            return $data;
        });
        
        return back()->with([
            'message' => 'Product updated successfully. Thanks',
            'data' => new ProductResource($data),
            'type' => 'bxs-check-circle'
        ]); 
    }
    
}
