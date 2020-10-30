<?php

namespace App\Jobs;

use App\Models\Event;
use App\Tools\Utils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    private $raceId;

    /**
     * Create a new job instance.
     *
     * @param int $raceId
     */
    public function __construct(int $raceId)
    {
        //
        $this->raceId = $raceId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        sleep((int)rand(1,5));
        sleep(1);
        $options = [
            'disciplineId' => '10',
            'raceId' => $this->raceId,
        ];

        $response = json_decode(Utils::download(env('UCI_RIDE_HOST') . env('UCI_RIDE_GET_EVENTS'), 1, $options));

        foreach ($response as $event) {
            Event::updateOrCreate([
                'race_id' => $this->raceId,
                'event_id' => $event->EventId,
                'event_name' => $event->EventName
            ]);
        }
    }
}
