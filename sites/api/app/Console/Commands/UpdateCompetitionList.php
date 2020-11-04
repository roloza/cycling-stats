<?php

namespace App\Console\Commands;

use App\Http\Controllers\CompetitionController;
use App\Tools\Utils;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateCompetitionList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update-competition-list {year?}';

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

        sleep((int)rand(1,5));

        Log::debug('[UpdateCompetitionList] Début du traitement (' . date("Y-m-d H:i:s") . ')');
        Log::debug('[UpdateCompetitionList] Arguments: ' . json_encode($this->arguments()));
        Log::debug('[UpdateCompetitionList] Options: ' . json_encode($this->options()));

        $year = $this->argument('year');
        $url = route('addCompetitions', ['year' => $year]); // On rajoute les nouvelles compétitions
        $response = Utils::download($url, 1);

        Log::debug('[UpdateCompetitionList] Fin du traitement (' . date("Y-m-d H:i:s") . ')');
    }
}
