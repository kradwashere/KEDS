<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'is_before' => ($this->is_before) ? true : false,
            'is_approved' => ($this->is_approved) ? true : false,
            'is_installed' => ($this->is_installed) ? true : false,
            'remarks' => $this->remarks,
            'quantity' => $this->quantity,
            'name' => $this->product->name
        ];
    }
}
