<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Building;
use Illuminate\Http\Request;
use App\Http\Resources\BuildingResource;
use App\Http\Requests\BuildingRequest;

class BuildingController extends Controller
{
    public function index(Request $request){
        $type = $request->type;
        switch($type){
            case 'lists':
                return $this->lists($request);
            break;
            default : 
            return inertia('Buildings/Index');
        }
    }

    public function store(BuildingRequest $request){
        $data = \DB::transaction(function () use ($request){
            if($request->editable){
                $data = Building::where('id',$request->id)->first();
                $data->update($request->except('editable'));
                return $data;
            }else{
                $data = Building::create($request->all());
                $lists = $request->lists;
                if(count($lists) > 0){
                    foreach($lists as $list){
                        for($x = 1; $x <= $list['bed']; $x++) {
                            $room = new Room;
                            $room->number = $x;
                            $room->floor = $list['floor'];
                            $room->price = $list['price'];
                            $room->building_id = $data->id;
                            $room->room_type = 1;
                            $room->bed_type = 3;
                            $room->save();
                        }
                   }
                }
                $data = Building::where('id',$data->id)->first();
                return $data;
            }
        });
        
        return back()->with([
            'message' => 'Building created successfully. Thanks',
            'data' => new BuildingResource($data),
            'type' => 'bxs-check-circle'
        ]); 
    }

    public function lists($request){
        $keyword = $request->keyword;
        $data = BuildingResource::collection(
            Building::when($keyword, function ($query, $keyword) {
                $query->where('name','LIKE','%'.$keyword.'%');
            })
            ->orderBy('name','ASC')
            ->paginate($request->counts)
            ->withQueryString()
        );
        return $data;
    }

    public function show($id){
        $data = Building::where('id',$id)->first();

        $rooms = [];
        $collection = Room::with('bedtype','roomtype')->where('building_id',$id)->get();
        $grouped = $collection->groupBy(function ($item, $key) {
            return substr($item['floor'], 0);
        });
        foreach($grouped as $key=>$group){
            $rooms[] = [
                'floor' => $key,
                'beds' => $group->chunk(10),
                'total' => Room::where('building_id',$id)->count()
            ];
        }

        return inertia('Buildings/View',[
            'building' => new BuildingResource($data),
            'rooms' => $rooms
        ]);
    }
}
