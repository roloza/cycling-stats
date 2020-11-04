<?php

namespace App\Http\Resources;

use App\Models\Competition;
use Illuminate\Http\Resources\Json\JsonResource;

class RaceResource extends JsonResource
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
            'competition_id' => $this->competition_id,
            'competition' => CompetitionResource::make($this->competition),
            'race_id' => $this->race_id,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'start_location' => $this->start_location,
            'end_location' => $this->end_location,
            'race_code' => $this->race_code,
            'race_name' => $this->race_name,
//            'events' =>  EventResource::collection($this->event)
        ];
    }
}
