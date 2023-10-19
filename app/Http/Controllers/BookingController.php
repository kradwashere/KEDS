<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Models\Building;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Resources\BookingResource;
use App\Http\Requests\BookingRequest;

class BookingController extends Controller
{
    public function index(Request $request){
        $type = $request->type;
        switch($type){
            case 'lists':
                return $this->lists($request);
            break;
            case 'rooms':
                return $this->rooms($request);
            break;
            default : 
            $customers = Customer::all();
            $buildings = Building::all();
            return inertia('Bookings/Index',['customers' => $customers, 'buildings' => $buildings]);
        }
    }

    public function rooms($request){
        $date = $request->date;
        $dates = explode(" ",$date);
        $data = []; $rooms = [];
        if(count($dates) == 3){
            $checkin = $dates[0].' 14:00:00';
            $checkout = $dates[2].' 12:00:00';

            $rooms = Room::with('roomtype','bedtype','building')
            ->when($date, function ($query) use ($checkin,$checkout){
                $query->whereHas('bookings',function ($query) use ($checkin,$checkout) {
                    $query->whereBetween('checkin_at',[$checkin,$checkout])->orWhereBetween('checkout_at',[$checkin,$checkout]);
                });
            })
            ->where('is_available',1)
            ->where('is_active',1)
            ->pluck('id');

            $data = Room::with('roomtype','bedtype','building')->whereNotIn('id',$rooms)->get();
        }

        return [
            'data' => $data,
            'checkin_at' => $checkin,
            'checkout_at' => $checkout
        ];
    }

    public function lists($request){
        $keyword = $request->keyword;
        $data = BookingResource::collection(
            Booking::with('customer','room','status','room.building','room.roomtype','room.bedtype')
            ->when($keyword, function ($query, $keyword) {
                $query->whereHas('customer',function ($query) use ($keyword) {
                    $query->where('name','LIKE','%'.$keyword.'%');
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->whereHas('status',function ($query) use ($status) {
                    $query->where('id',$status);
                });
            })
            ->orderBy('created_at','ASC')
            ->paginate($request->counts)
            ->withQueryString()
        );
        return $data;
    }

    public function store(Request $request){

        $validated = $request->validate([
            'room_id' => 'required',
            'customer_id' => 'required',
            'checkin_at' => 'required',
            'checkout_at' => 'required'
        ]);

        $data = \DB::transaction(function () use ($request){
            $data = Booking::create(array_merge($request->all(),['status_id' => 1]));
            return $data;
        });

        return back()->with([
            'message' => 'Successfully Added',
            'data' => new BookingResource($data),
            'type' => 'bxs-check-circle'
        ]); 
    }

    public function update(Request $request)
    {   
        $data = \DB::transaction(function () use ($request){
          
            $data = Booking::findOrFail($request->id);
            $data->update($request->except('type'));
            $room_id = $data->room->id;
            if($request->type == 'checkin'){
                $room = Room::where('id',$room_id)->update(['is_available' => 0]);
            }else{
                $room = Room::where('id',$room_id)->update(['is_available' => 1]);
            }
            return $data;
        });
        
        return back()->with([
            'message' => 'Booking updated successfully. Thanks',
            'data' => new BookingResource($data),
            'type' => 'bxs-check-circle'
        ]); 
    }

}
