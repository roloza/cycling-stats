<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResultResource extends JsonResource
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
            'event_id' => $this->event_id,
//            'event' => EventResource::make($this->event),
            'result_id' => $this->result_id,
            'rank' => $this->rank,
            'name' => $this->name,
            'country' => $this->country,
            'age' => $this->age,
            'rider_id' => $this->rider_id,
//            'rider' => $this->rider,
//            'rider' => RiderResource::make($this->rider),
            'value' => $this->value,
            'country_name' => $this->country_name,
            'iso_code' => $this->iso_code,
            'team_name' => $this->team_name,
            'team_id' => $this->team_id,
//            'team' => $this->team,
//            'team' => TeamResource::make($this->team),
            'point_pcr' => $this->point_pcr,
            'retire' => $this->retire
        ];
    }
}
