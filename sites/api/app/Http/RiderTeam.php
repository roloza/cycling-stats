<?php


namespace App;
use Illuminate\Database\Eloquent\Model;


class RiderTeam extends Model
{
    protected $table = 'rider_team';

    protected $fillable = [
        'id_rider',
        'id_team',
    ];
}
