<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'event';

    protected $fillable = [
        'race_id',
        'event_id',
        'event_name'
    ];

    public function race()
    {
        return $this->belongsTo(Race::class, 'race_id', 'race_id');
    }

    public function result()
    {
        return $this->hasMany(Result::class, 'event_id', 'event_id');
    }
}
