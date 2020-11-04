<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    use HasFactory;

    protected $table = 'race';

    protected $fillable = [
        'competition_id',
        'race_id',
        'start_at',
        'end_at',
        'start_location',
        'end_location',
        'race_code',
        'race_name'
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class, 'competition_id', 'competition_id');
    }

    public function event()
    {
        return $this->hasMany(Event::class, 'race_id', 'race_id');
    }
}
