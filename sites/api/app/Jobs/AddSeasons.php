<?php

namespace App\Jobs;

use App\Models\Season;
use App\Tools\Utils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class AddSeasons implements ShouldQueue
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
//        sleep((int)rand(2,5));
        sleep(1);

        $response = json_decode(Utils::download(env('UCI_RIDE_HOST') . env('UCI_RIDE_GET_DISCIPLINE_SEASONS') . '?disciplineId=10', 0));

        foreach($response as $season) {
            Season::updateOrCreate([
                'year' => (int)$season->Year
            ], [
                'season_id' => (int)$season->Id
            ]);
        }

    }
}
