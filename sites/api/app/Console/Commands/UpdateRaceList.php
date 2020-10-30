<?php

namespace App\Console\Commands;

use App\Models\Competition;
use App\Models\Race;
use App\Tools\Utils;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateRaceList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update-race-list {current?} {--competitionId=*} {--year=*} {--dateStartAfter=*} {--new-only=*}';

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
        Log::debug('[UpdateRaceList] Début du traitement (' . date("Y-m-d H:i:s") . ')');
        Log::debug('[UpdateRaceList] Arguments: ' . json_encode($this->arguments()));
        Log::debug('[UpdateRaceList] Options: ' . json_encode($this->options()));

        $argCurrent = $this->argument('current');
        $optCompetitionIds = $this->option('competitionId');
        $optYears = $this->option('year');
        $optDateStartAfter = $this->option('dateStartAfter');
        $newOnly = (bool)current($this->option('new-only'));


        $competitionsIds = []; // Liste des identitifant de compétition à traiter

        // Cas 1 : On traite uniquement les compétitions 'en cours'
        if ($argCurrent !== null ) {
            Log::debug('[UpdateRaceList] On traite uniquement les compétitions en cours');

            $competitions = Competition::where('is_in_progress', true)->get();
            foreach ($competitions as $competition) {
                $competitionsIds[] = $competition->competition_id;
            }
        }
        // Cas 2 : On traite uniquement les compétitions 'par année'
        elseif (sizeof($optYears)  > 0) {
            Log::debug('[UpdateRaceList] On traite uniquement les compétitions par année');

            foreach ($optYears as $year) {
                $competitions = Competition::where('year', $year)->get();
                foreach ($competitions as $competition) {
                    $competitionsIds[] = $competition->competition_id;
                }
            }
        }
        // Cas 3 : On traite uniquement les compétitions démarrées après la date en paramètre
        elseif (sizeof($optDateStartAfter)  > 0) {
            Log::debug('[UpdateRaceList] On traite uniquement les compétitions démarrées après le ' . current($optDateStartAfter));
            $competitions = Competition::whereDate('start_at', '>=',  current($optDateStartAfter))->get();
            foreach ($competitions as $competition) {
                $competitionsIds[] = $competition->competition_id;
            }
        }

        // Cas 4 : On passe en option des identifiants de competition
        elseif (sizeof($optCompetitionIds)  > 0) {
            Log::debug('[UpdateRaceList] On passe en option des identifiants de competition');
            $competitionsIds = $optCompetitionIds;
        }
        // Cas 5 : Mode init, on met à jour toutes les compétitions
        else {
            Log::debug('[UpdateRaceList] Mode init, on met à jour toutes les compétitions');
            $competitions = Competition::get();
            foreach ($competitions as $competition) {
                $competitionsIds[] = $competition->competition_id;
            }
        }

        // On lance la récupération des courses
        foreach ($competitionsIds as $competitionId) {
            if ($newOnly) { // On ne récupère que les nouvelles courses. Les anciennes ne sont pas MAJ
                $races = Race::where('competition_id', $competitionId)->get();
                if ($races->count() !== 0) {
                    Log::debug('[UpdateRaceList] Cette course (' . $competitionId . ') existe déja. On ne la met pas à jour');
                    continue;
                }
            }
            $url = route('addRaces', ['competitionId' => $competitionId]);
            Log::debug('[UpdateRaceList] Route: ' . $url);
            $response = Utils::download($url, 1);
            Log::debug('[UpdateRaceList] Response: ' . $response);
        }

        Log::debug('[UpdateRaceList] Fin du traitement (' . date("Y-m-d H:i:s") . ')');
        return 0;
    }


}
