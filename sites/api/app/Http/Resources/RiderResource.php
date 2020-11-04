<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RiderResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'id_pcm' => $this->id_pcm,
            'pcm' => PcmResource::make($this->pcm),
            'slug' => $this->slug,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'nationality' => $this->nationality,
            'date_of_birth' => $this->date_of_birth,
            'weight' => $this->weight,
            'height' => $this->height,
            'profil_twitter' => $this->profil_twitter,
            'profil_instagram' => $this->profil_instagram,
            'profil_facebook' => $this->profil_facebook,
            'profil_strava' => $this->profil_strava,
            'website' => $this->website,
            'visual' => $this->visual,
            'current_team' => $this->current_team,
            'uci_points' => $this->uci_points,
            'uci_position' => $this->uci_position
        ];
    }
}
