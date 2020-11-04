<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Race;
use App\Tools\Utils;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateEventList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update-event-list {current?} {--raceId=*} {--dateStartAfter=*} {--new-only=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::debug('[UpdateEventList] Début du traitement (' . date("Y-m-d H:i:s") . ')');
        Log::debug('[UpdateEventList] Arguments: ' . json_encode($this->arguments()));
        Log::debug('[UpdateEventList] Options: ' . json_encode($this->options()));

        $argCurrent = $this->argument('current');
        $optDateStartAfter = $this->option('dateStartAfter');
        $optRacesIds = $this->option('raceId');
        $newOnly = (bool)current($this->option('new-only'));

        $raceIds = []; // Liste des identitifant des courses/étapes à traiter

        // Cas 1 : On traite uniquement les courses/étapes du jour
        if ($argCurrent !== null ) {
            Log::debug('[UpdateEventList] On traite uniquement les courses/étapes du jour');

            $races = Race::whereDate('start_at', date('Y-m-d'))->get();

            foreach ($races as $race) {
                $raceIds[] = $race->race_id;
            }
        }

        // Cas 2 : On traite uniquement les courses/étapes démarrées après la date en paramètre
        elseif (sizeof($optDateStartAfter)  > 0) {
            Log::debug('[UpdateEventList] On traite uniquement les courses/étapes démarrées après le ' . current($optDateStartAfter));
            $races = Race::whereDate('start_at', '>=',  current($optDateStartAfter))->get();
            foreach ($races as $race) {
                $raceIds[] = $race->race_id;
            }
        }

        // Cas 3 : On passe en option des identifiants de courses/étapes
        elseif (sizeof($optRacesIds)  > 0) {
            Log::debug('[UpdateEventList] On passe en option des identifiants de courses/étapes');
            $raceIds = $optRacesIds;
        }

        // Cas 5 : Mode init, on met à jour toutes les courses/étapes
        else {
            Log::debug('[UpdateEventList] Mode init, on met à jour toutes les courses/étapes');
            $races = Race::get();
            foreach ($races as $races) {
                $raceIds[] = $races->race_id;
            }
        }

        // On lance la récupération des events
        $cpt = 0;
        $total = sizeof($raceIds);
        foreach ($raceIds as $raceId) {
            $cpt++;
            Log::debug('[UpdateEventList] Récupération des events: [' . $cpt . '/' . $total . ']');
            if ($newOnly) { // On ne récupère que les nouvelles étape/course. Les anciennes ne sont pas MAJ
                $races = Event::where('race_id', $raceId)->get();
                if ($races->count() !== 0) {
                    Log::debug('[UpdateEventList] Cette étape/course (' . $raceId . ') existe déja. On ne la met pas à jour');
                    continue;
                }
            }
            $url = route('addEvents', ['raceId' => $raceId]);
            Log::debug('[UpdateEventList] Route: ' . $url);
            $response = Utils::download($url, 1);
            Log::debug('[UpdateEventList] Response: ' . $response);
        }

        Log::debug('[UpdateEventList] Fin du traitement (' . date("Y-m-d H:i:s") . ')');
        return 0;
    }
}
