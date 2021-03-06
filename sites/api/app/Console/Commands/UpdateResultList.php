<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Result;
use App\Tools\Utils;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateResultList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update-result-list {current?} {--eventId=*} {--new-only=*}';

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

        Log::debug('[UpdateResultList] Début du traitement (' . date("Y-m-d H:i:s") . ')');
        Log::debug('[UpdateResultList] Arguments: ' . json_encode($this->arguments()));
        Log::debug('[UpdateResultList] Options: ' . json_encode($this->options()));

        $argCurrent = $this->argument('current');
        $optEventIds = $this->option('eventId');
        $newOnly = (bool)current($this->option('new-only'));

        $eventIds = []; // Liste des identitifant des étapes à traiter

        // Cas 1 : On traite uniquement les courses/étapes du jour
        if ($argCurrent !== null ) {
            Log::debug('[UpdateResultList] On traite uniquement les nouveaux résultats');

            $events = Event::orderBy('created_at', 'DESC')->get();

            foreach ($events as $event) {
                $countResults = Result::where('event_id', $event->event_id)->count();
                // S'il n'y a pas, pour cet étape, suffisamment de données dans les résultats
                if ($countResults === 0) {
                    $eventIds[] = $event->event_id;
                }
            }
        } elseif (sizeof($optEventIds)  > 0) {
            Log::debug('[UpdateResultList] On passe en option des identifiants d\'evenements');
            $eventIds = $optEventIds;
        } else {
            $events = Event::orderBy('created_at', 'DESC')->get();
            Log::debug('[UpdateResultList] Mode init, on met à jour tous les résultats');

            foreach ($events as $event) {
                $eventIds[] = $event->event_id;
            }
        }
        // On lance la récupération des resultats
        $cpt = 0;
        $total = sizeof($eventIds);
        foreach ($eventIds as $eventId) {
            $cpt++;
            Log::debug('[UpdateResultList] Récupération des resultats: [' . $cpt . '/' . $total . ']');
            if ($newOnly) { // On ne récupère que les nouvelles courses. Les anciennes ne sont pas MAJ
                $results = Result::where('event_id', $eventId)->get();

                if ($results->count() !== 0) {
                    Log::debug('[UpdateResultList] Ce résultat (' . $eventId . ') existe déja. On ne la met pas à jour');
                    continue;
                }
            }
            $url = route('addResults', ['eventId' => $eventId]);
            Log::debug('[UpdateResultList] Route: ' . $url);
            $response = Utils::download($url, 1);
            Log::debug('[UpdateResultList] Response: ' . $response);
        }

        Log::debug('[UpdateResultList] Fin du traitement (' . date("Y-m-d H:i:s") . ')');
        return 0;
    }
}
