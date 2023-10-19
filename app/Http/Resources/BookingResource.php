<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->status->name == 'Pending'){
            $status = 'bg-soft-warning';
            $type = 'checkin';
        }else if($this->status->name == 'Ongoing'){
            $status = 'bg-soft-success';
            $type = 'checkout';
        }else if($this->status->name == 'Cancelled'){
            $status = 'bg-soft-danger';
            $type = '';
        }else{
            $status = '';
            $type = '';
        }
        return [
            'customer' => $this->customer,
            'room' => $this->room,
            'status' => $this->status,
            'price' => $this->price,
            'checkin_at' => $this->checkin_at,
            'checkout_at' => $this->checkout_at,
            'is_paid' => $this->is_paid,
            'is_walkin' => $this->is_walkin,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'color' => $status,
            'type' => $type

        ];
    }
}
