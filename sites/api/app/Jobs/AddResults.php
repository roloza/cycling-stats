<?php

namespace App\Jobs;

use App\Models\Result;
use App\Rider;
use App\Team;
use App\Tools\Utils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class AddResults implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    private $eventId;

    /**
     * Create a new job instance.
     *
     * @param int $eventId
     */
    public function __construct(int $eventId)
    {
        //
        $this->eventId = $eventId;
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
            'eventId' => $this->eventId,
            'take' => '100',
            'skip' => null,
            'page' => null,
            'pageSize' => '100',
        ];

        for ($page = 1; $page <= 2; $page++) {
            $options['skip'] = ($page * 100) -1;
            $options['page'] = $page;
            $response = json_decode(Utils::download(env('UCI_RIDE_HOST') . env('UCI_RIDE_GET_RESULTS'), 1, $options));
            if(!isset($response->data)) continue;

            foreach ($response->data as $result) {
                if ($result->IndividualDisplayName === null) continue;
                $rider = Rider::select('id')->where('slug', Str::slug($result->IndividualDisplayName))->first();

                $team = Team::select('id')
                    ->where('slug', Str::slug(Utils::parseCompetitionDate($result->MandatoryDate)['year'] . ' ' . $result->TeamName))
                    ->first();

                Result::updateOrCreate([
                    'event_id' => $this->eventId,
                    'rank' => $result->RankNumber !== null ? (int)$result->RankNumber : $result->SortOrder,
                ], [
                    'result_id' => $result->ResultId,
                    'name' => $result->IndividualDisplayName,
                    'country' => $result->IndividualCountryName,
                    'age' => $result->Age,
                    'rider_id' => $rider ? $rider->id : null,
                    'value' => $result->ResultValue,
                    'country_name' => $result->IndividualCountryNameText,
                    'iso_code' => $result->IsoCode2,
                    'team_name' => $result->TeamName,
                    'team_id' => $team ? $team->id : null,
                    'point_pcr' => $result->TeamPointPcR,
                    'retire' => $result->Irm
                ]);
            }
        }
    }
}
