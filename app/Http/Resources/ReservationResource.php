<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $this->resource->load(['photographer']);

        return [
            'id' => $this->id,
            'picture_count' => $this->picture_count,
            'reservation_time' => $this->reservation_time,
            'status' => $this->status,
            'photographer' => [
                'firs_name' => $this->photographer->first_name,
                'last_name' => $this->photographer->last_name,
                'phone_number' => $this->photographer->phone_number,
                'avatar' => $this->photographer->avatar
            ]
        ];
    }
}
