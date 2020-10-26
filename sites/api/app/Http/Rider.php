<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    protected $fillable = [
        'name',
        'id_pcm',
        'slug',
        'firstname',
        'lastname',
        'nationality',
        'date_of_birth',
        'weight',
        'height',
        'profil_twitter',
        'profil_instagram',
        'profil_facebook',
        'profil_strava',
        'website',
        'visual',
        'current_team',
        'uci_points',
        'uci_position'
    ];


    public function pcm()
    {
        return $this->belongsTo(Pcm::class, 'id_pcm');
    }
}
