<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $table = 'competition';

    protected $fillable = [
        'competition_id',
        'year',
        'start_at',
        'end_at',
        'name',
        'name_aff',
        'slug',
        'slug_aff',
        'is_in_progress',
        'is_done',
        'country_code',
        'country',
        'class_code',
        'date',
        'count_stages'
    ];

    public function race()
    {
        return $this->hasMany(Race::class, 'competition_id', 'competition_id');
    }

}
