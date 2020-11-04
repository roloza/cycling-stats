<?php

namespace App\Models;

use App\Rider;
use App\Team;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $table = 'result';

    protected $fillable = [
        'event_id',
        'result_id',
        'rank',
        'name',
        'country',
        'age',
        'rider_id',
        'value',
        'country_name',
        'iso_code',
        'team_name',
        'team_id',
        'point_pcr',
        'retire'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function rider()
    {
        return $this->hasOne(Rider::class, 'id', 'rider_id');
    }

    public function team()
    {
        return $this->hasOne(Team::class, 'id', 'team_id');
    }
}
