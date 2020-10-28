<?php

namespace App\Models;

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
}
