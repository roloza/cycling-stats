<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'fullname',
        'shortname',
        'slug',
        'category',
        'code',
        'continent_code',
        'country_code',
        'country_code_iso',
        'discipline_code',
        'website',
        'season_year',
        'team_history_id',
        'team_name_id',
        'team_order',
        'team_parent_id',
        'team_season_id'
    ];
}
