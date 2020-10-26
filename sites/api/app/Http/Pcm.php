<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pcm extends Model
{
    protected $table = 'pcm';

    protected $fillable = [
        'prenom',
        'nom',
        'slug',
        'grimpeur',
        'descendeur',
        'puncheur',
        'rouleur',
        'gestionEffort',
        'sprinter',
        'panache',
        'taille',
        'poids',
        'region',
        'popularite',
        'potentiel',
        'dateDeNaissance',
        'moyenne'
    ];
}
