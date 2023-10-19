<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class JobResource extends JsonResource
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
            'ids' => Crypt::encryptString($this->id),
            'code' => $this->code,
            'total' => $this->total,
            'model' => $this->model,
            'serial_no' => $this->serial_no,
            'remarks' => $this->remarks,
            'has_response' => $this->has_response,
            'is_paid' => $this->is_paid,
            'is_pickup' => $this->is_pickup,
            'has_warranty' => $this->has_warranty,
            'unit' => $this->unit,
            'brand' => $this->brand,
            'status' => $this->status,
            'client' => $this->client,
            'technician' => $this->technician,
            'images' => $this->images,
            'services' => ServiceResource::collection($this->services),
            'parts' => PartResource::collection($this->parts),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'count' => count($this->services) + count($this->parts),
            'done' => $this->services->where('is_completed',1)->count() + $this->parts->where('is_installed',1)->count(),
            'created' => date('d M, Y g:i a', strtotime($this->created_at)),
            'updated' => date('d M, Y', strtotime($this->updated_at)),
            'created_at' => date('M d, Y g:i a', strtotime($this->created_at)),
        ];
    }
}
