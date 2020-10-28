<?php

namespace App\Jobs;

use App\Models\Race;
use App\Tools\Utils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class AddRaces
 * @package App\Jobs
 */
class AddRaces implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    private $competitionId;
    /**
     * Create a new job instance.
     *
     * @param int $competitionId
     */
    public function __construct(int $competitionId)
    {
        $this->competitionId = $competitionId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $options = [
            'disciplineId' => '10',
            'competitionId' => $this->competitionId,
            'take' => '40',
            'skip' => '0',
            'page' => '1',
            'pageSize' => '40',
        ];

        $response = json_decode(Utils::download(env('UCI_RIDE_HOST') . env('UCI_RIDE_GET_RACES'), 1, $options));
        if(!isset($response->data)) return;
        foreach ($response->data as $race) {

            $startAt = new \DateTime(Utils::parseCompetitionDate($race->StartDate)['date']);
            $endAt = new \DateTime(Utils::parseCompetitionDate($race->EndDate)['date']);

            Race::updateOrCreate([
                'competition_id' => $this->competitionId,
                'race_id' => $race->Id,
                'start_at' => $startAt,
                'end_at' => $endAt,
                'start_location' => $race->StartLocation,
                'end_location' => $race->EndLocation,
                'race_code' => $race->RaceTypeCode,
                'race_name' => $race->RaceName
            ]);
        }
    }
}
