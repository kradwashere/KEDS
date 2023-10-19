<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Room;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\Dropdown;
use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Http\Resources\UserResource;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        $info = [
            Customer::count(),
            Booking::where('status_id',1)->count(),
            Booking::where('status_id',2)->count(), 
            Booking::where('is_paid',1)->sum('price')
        ];

        $rooms = Dropdown::where('type','Room Type')->withCount('rooms')->orderBy('rooms_count', 'desc')->get();
        $rooms_availability = [];
        foreach($rooms as $room){
            $rooms_availability[] = [
                'room' => $room->name,
                'count' => $room->rooms_count,
                'available' => Room::where('room_type',$room->id)->where('is_available',1)->count(),
                'not_available' => Room::where('room_type',$room->id)->where('is_available',0)->count(),
                'percentage' => ($room->rooms_count != 0) ? (Room::where('room_type',$room->id)->where('is_available',0)->count() / $room->rooms_count) * 100 : 0
            ];
        }

        $beds = Dropdown::where('type','Bed Type')->withCount('beds')->orderBy('beds_count', 'desc')->get();
        $beds_availability = [];
        foreach($beds as $bed){
            $beds_availability[] = [
                'bed' => $bed->name,
                'count' => $bed->beds_count,
                'available' => Room::where('bed_type',$bed->id)->where('is_available',1)->count(),
                'not_available' => Room::where('bed_type',$bed->id)->where('is_available',0)->count(),
                'percentage' => ($bed->beds_count != 0) ? (Room::where('bed_type',$bed->id)->where('is_available',0)->count() / $bed->beds_count) * 100 : 0
            ];
        }


        return array_merge(parent::share($request), [
            'auth' => (\Auth::check()) ? new UserResource(\Auth::user()) : '',
            'flash' => [
                'message' => session('message'),
                'datares' => session('data'),
                'type' => session('type')
            ],
            'dropdowns' => Dropdown::orderBy('name','ASC')->get(),
            'info' => $info,
            'beds' => $beds_availability,
            'rooms' => $rooms_availability,
            'customers' => Customer::all(),
            'checkins' => Booking::with('room','room.building','room.roomtype','room.bedtype','customer','status')->where('status_id',1)->whereDate('checkin_at',now())->get(),
            'checkouts' => Booking::with('room','room.building','room.roomtype','room.bedtype','customer','status')->where('status_id',2)->whereDate('checkout_at',now())->get(),
            'currents' => Booking::with('room','room.building','room.roomtype','room.bedtype','customer','status')->where('status_id',2)->get(),
        ]);
    }
}
