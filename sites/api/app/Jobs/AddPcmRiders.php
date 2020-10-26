<?php

namespace App\Jobs;

use App\Pcm;
use App\Tools\Utils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class AddPcmRiders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pcmRiders = $this->parsePcmRiders();
        foreach ($pcmRiders as $slug => $pcmRider) {
            $pcm = new Pcm();
            $pcm->prenom = $pcmRider['prenom'];
            $pcm->nom = $pcmRider['nom'];
            $pcm->slug = $slug;
            $pcm->grimpeur = $pcmRider['grimpeur'];
            $pcm->descendeur = $pcmRider['descendeur'];
            $pcm->puncheur = $pcmRider['puncheur'];
            $pcm->rouleur = $pcmRider['rouleur'];
            $pcm->gestionEffort = $pcmRider['gestionEffort'];
            $pcm->sprinter = $pcmRider['sprinter'];
            $pcm->panache = $pcmRider['panache'];
            $pcm->taille = $pcmRider['taille'];
            $pcm->poids = $pcmRider['poids'];
            $pcm->region = $pcmRider['region'];
            $pcm->popularite = $pcmRider['popularite'];
            $pcm->potentiel = $pcmRider['potentiel'];
            $pcm->dateDeNaissance = $pcmRider['dateDeNaissance'];
            $pcm->moyenne = $pcmRider['moyenne'];

            if (Pcm::where(['slug' => $slug])->first()) {
                $pcm->update();
            } else {
                $pcm->save();
            }
        }

    }

    /**
     * Stats PCM des coureurs depuis un fichier
     * @return array
     */
    private function parsePcmRiders() {
        $riders = [];
        $path = public_path('files/pcm-riders-2020-08-06.csv');
        $records = Utils::readAsList($path);

        foreach($records as $record) {
            $slug = Str::slug($record['nom'].' '.$record['prenom']);

            $riders[$slug] = $this->setStats($record);
        }
        return $riders;
    }

    /**
     * Set les statistiques d'un coureur
     * @param $record
     * @return array
     */
    private function setStats($record) {

        $dateDeNaissance = \DateTime::createFromFormat('d/m/Y', $record['dateDeNaissance']);
        if ($dateDeNaissance instanceof \DateTime) {
            $dateDeNaissance = $dateDeNaissance->format('Y-m-d');
        } else {
            $dateDeNaissance = null;
        }
        $stats = [
            'prenom' => $record['prenom'],
            'nom' => $record['nom'],
            'grimpeur' => $record['carac_montagne'],
            'descendeur' => $record['carac_descente'],
            'puncheur' => $record['carac_vallon'],
            'rouleur' => intval(($record['carac_clm'] + $record['carac_prologue']) / 2),
            'gestionEffort' => intval(($record['carac_endurance'] + $record['carac_resistance'] + $record['carac_recuperation']) / 3),
            'sprinter' => intval(($record['carac_sprint'] + $record['carac_acceleration']) / 2),
            'panache' => $record['carac_baroudeur'],
            'taille' => $record['taille'],
            'poids' => $record['poids'],
            'region' => $record['region'],
            'popularite' => $record['Popularite'],
            'potentiel'  => $record['potentiel'],
            'dateDeNaissance' => $dateDeNaissance,
        ];
        $stats['moyenne'] = intval(($stats['grimpeur'] + $stats['descendeur'] + $stats['puncheur'] + $stats['rouleur'] + $stats['gestionEffort'] + $stats['sprinter'] + $stats['panache'] ) / 7);
        return $stats;
    }
}
