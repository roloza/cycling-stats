<?php

namespace App\Jobs;

use App\Models\Competition;
use App\Models\Season;
use App\Tools\Utils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class AddCompetitions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    private $year;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $year)
    {
        //
        $this->year = $year;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $res = Season::select('season_id')->where('year', $this->year)->firstOrFail();
        $seasonId = $res->season_id;
        $options = [
            'disciplineId' => 10,
            'take' => 5000,
            'skip' => 0,
            'page' => 1,
            'pageSize' => 5000,
            'sort[0][field]' => 'StartDate',
            'sort[0][dir]' => 'desc',
            'filter[filters][0][field]' =>  'RaceTypeId',
            'filter[filters][0][value]' =>  0,
            'filter[filters][1][field]' =>  'CategoryId',
            'filter[filters][1][value]' =>  22,
            'filter[filters][2][field]' =>  'SeasonId',
            'filter[filters][2][value]' =>  $seasonId
        ];
        $response = json_decode(Utils::download(env('UCI_RIDE_HOST') . env('UCI_RIDE_GET_COMPETITIONS'), 1, $options));
        foreach($response->data as $competition) {

            $startAt = new \DateTime(Utils::parseCompetitionDate($competition->StartDate)['date']);
            $endAt = new \DateTime(Utils::parseCompetitionDate($competition->EndDate)['date']);

            Competition::updateOrCreate([
                'slug' => Str::slug($this->year . '-' .$competition->CompetitionName)
            ], [
                'competition_id' => $competition->CompetitionId,
                'year' => $this->year,
                'start_at' => $startAt,
                'end_at' => $endAt,
                'name' => $competition->CompetitionName,
                'name_aff' => $competition->CompetitionName,
                'slug_aff' => Str::slug($competition->CompetitionName),
                'is_in_progress' => $competition->IsInProgress,
                'is_done' => $competition->IsDone,
                'country_code' => $competition->CountryIsoCode3,
                'country' => $competition->CountryName,
                'class_code' => $competition->ClassCode,
                'date' => $competition->Date,
                'count_stages' => (int)$endAt->diff($startAt)->format("%a") + 1
            ]);
        }
    }
}
