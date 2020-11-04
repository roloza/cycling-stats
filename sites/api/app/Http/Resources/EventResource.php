<?php

namespace App\Http\Resources;

use App\Models\Race;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'race_id' => $this->race_id,
            'race' => RaceResource::make($this->race),
            'event_id' => $this->event_id,
            'event_name' => $this->event_name,
//            'results' => ResultResource::collection($this->result)
        ];
    }
}
