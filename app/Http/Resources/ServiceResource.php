<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'is_completed' => ($this->is_completed) ? true : false,
            'remarks' => $this->remarks,
            'service' => $this->service,
        ];
    }
}
