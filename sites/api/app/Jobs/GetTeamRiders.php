<?php

namespace App\Jobs;

use App\Pcm;
use App\Rider;
use App\RiderTeam;
use App\Team;
use App\Tools\Utils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GetTeamRiders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $team;

    /**
     * Create a new job instance.
     *
     * @param $team
     */
    public function __construct(Team $team)
    {
        //
        $this->team = $team;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $urlToDl = env('UCI_HOST') . env('UCI_GET_TEAM_HISTORY') . '?disciplineCode=' . $this->team->discipline_code . '&parentId=' . $this->team->team_parent_id. '&historyId=' . $this->team->team_history_id;
        Log::debug($urlToDl);

        $response = json_decode(Utils::download($urlToDl, 0));
        if (!isset($response->Riders)) {
            Log::debug('Récupération de données impossibles');
            return;
        }
        // S'il y a des neo pro, on les ajoutent à la liste des coureurs
        if (isset($response->Neo)) {
            $response->Riders = array_merge($response->Riders, $response->Neo);
        }

        // S'il y a des stagiaires, on les ajoutent à la liste des coureurs
        if (isset($response->Trainees)) {
            $response->Riders = array_merge($response->Riders, $response->Trainees);
        }
        foreach($response->Riders as $uciRider) {
            if ($uciRider->IndividualName === null) {
                continue;
            }
            $slug = Str::slug($uciRider->IndividualName);

            Log::debug('On traite le coureur : ' . $slug);
            $pcmRider = Pcm::where(['slug' => $slug])->first();
            if ($pcmRider instanceof Pcm) {
                Log::debug('Coureur trouvé sur PCM');
            } else {
                Log::warning('Coureur non trouvé sur PCM');
            }
            $dateOfBirth = \DateTime::createFromFormat('d.m.Y', $uciRider->BirthdateStr);

            $rider = [
                'name' => $uciRider->IndividualName,
                'slug' => $slug,
                'firstname' => ($pcmRider !== null) ? $pcmRider->prenom : "",
                'lastname' => ($pcmRider !== null) ? $pcmRider->nom : "",
                'nationality' => $uciRider->CountryCode,
                'date_of_birth' => ($dateOfBirth instanceof \DateTime) ? $dateOfBirth : null,
                'id_pcm' => ($pcmRider !== null) ? $pcmRider->id : 0,
                'weight' => ($pcmRider !== null) ? $pcmRider->taille : null,
                'height' => ($pcmRider !== null) ? $pcmRider->poids : null,
            ];
            if ($riderToUpdate = Rider::where(['slug' => $slug])->first()) {
                $riderToUpdate->update($rider);
                Log::debug('Update');
            } else {
                $riderToAdd = Rider::create($rider);
                $riderToAdd->save();
                Log::debug('Save');
            }

            $rider = Rider::where(['slug' => $slug])->first();
            if (!RiderTeam::where(['id_team' => $this->team->id, 'id_rider' => $rider->id])->first()) {
                $riderTeam = new RiderTeam();
                $riderTeam->id_rider = $rider->id;
                $riderTeam->id_team = $this->team->id;
                $riderTeam->save();
            }
        }
    }
}
