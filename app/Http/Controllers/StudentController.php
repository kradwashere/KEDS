<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;
use App\Http\Requests\StudentRequest;

class StudentController extends Controller
{
    public function index(Request $request){
        if($request->search){
            $data = StudentResource::collection(
                User::query()
                ->with('profile','student.year','student.course','student.status')
                ->when($request->keyword, function ($query, $keyword) {
                    $query->whereHas('profile',function ($query) use ($keyword) {
                        $query->where(\DB::raw('concat(firstname," ",lastname)'), 'LIKE', "%{$keyword}%")
                        ->orWhere(\DB::raw('concat(lastname," ",firstname)'), 'LIKE', "%{$keyword}%");
                    })->orWhere(function ($query) use ($keyword) {
                        $query->where('username', 'LIKE', "%{$keyword}%")->where('role','Student');
                    });
                })
                ->where('role','Student')
                ->paginate(10)
                ->withQueryString()
            );
            return $data;
        }else{
            return inertia('Modules/Students/Index');
        }
    }

    public function store(StudentRequest $request){
        $data = \DB::transaction(function () use ($request){
            $year = date('y');
            $month = date('m');
            $count = User::where('role','Student')->count();
            $username = $year.str_pad(($count+1), 3, '0', STR_PAD_LEFT);  

            $data = User::create(array_merge($request->all(), ['username' => $username, 'role' => 'Student','password' => bcrypt('123456789')]));
            $data->profile()->create($request->all());
            $data->student()->create(array_merge($request->all(), ['status_id' => 7]));
            return $data;
        });

        return back()->with([
            'message' => 'Student created successfully. Thanks',
            'data' => new StudentResource($data),
            'type' => 'bxs-check-circle'
        ]); 
    }

    public function show(Request $request){
        $data = new StudentResource(User::findOrFail($request->student));
        return inertia('Modules/Students/Profile',['user' => $data]);
    }
}
