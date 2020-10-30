<?php

namespace App\Jobs;

use App\Team;
use App\Tools\Utils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class GetTeam implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $year;
    /**
     * Create a new job instance.
     *
     * @param string $year
     */
    public function __construct($year)
    {
       $this->year = $year;
    }

    /**
     * Execute the job.
     * Permet de récupérer la liste des équipes pour une année et les enregistres en BDD
     *
     * @return void
     */
    public function handle()
    {
//        sleep((int)rand(1,5));
        sleep(1);

        $options = [
            'disciplineCode' => 'ROA',
            'culture' => 'fr',
            'take' => 5000,
            'page' => 1,
            'pageSize' => 5000,
            'filter[filters][0][field]' =>  'Year',
            'filter[filters][0][value]' =>  $this->year,
            'filter[filters][1][field]' =>  'TeamSeasonId',
            'filter[filters][1][value]' =>  0,
            'filter[filters][2][field]' =>  'TeamName',
            'filter[filters][2][value]' =>  '',
            'filter[filters][3][field]' =>  'CountryCode',
            'filter[filters][3][value]' =>  '',
            'filter[filters][4][field]' =>  'FunctionId',
            'filter[filters][4][value]' =>  48,
            'filter[filters][5][field]' =>  'ContinentCode',
            'filter[filters][5][value]' =>  '',
        ];
        $response = json_decode(Utils::download(env('UCI_HOST') . env('UCI_GET_TEAMS'), 1, $options));

        foreach($response->data as $uciTeam) {
            Team::updateOrCreate([
                'slug' => Str::slug($uciTeam->SeasonYear . '-' .$uciTeam->NameFra)
            ],[
                'fullname' => $uciTeam->NameFra,
                'shortname' => $uciTeam->TeamCode,
                'category' => $uciTeam->Category,
                'code' => $uciTeam->Code,
                'continent_code' => $uciTeam->ContinentCode,
                'country_code' => $uciTeam->CountryCode,
                'country_code_iso' => $uciTeam->CountryIso,
                'discipline_code' => $uciTeam->DisciplineCode,
                'website' => $uciTeam->WebSite,
                'season_year' => $uciTeam->SeasonYear,
                'team_history_id' => $uciTeam->TeamHistoryId,
                'team_name_id' => $uciTeam->TeamNameId,
                'team_order' => $uciTeam->TeamOrder,
                'team_parent_id' => $uciTeam->TeamParentId,
                'team_season_id' => $uciTeam->TeamSeasonId
            ]);
        }
    }
}
