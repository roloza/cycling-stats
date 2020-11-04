<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Jobs\AddEvents;
use App\Models\Event;
use Illuminate\Http\Request;

/**
 * Class EventController
 * @package App\Http\Controllers
 */
class EventController extends Controller
{
    /**
     * Affiche la liste des classification d'une course via son ID de course
     * @param $raceId
     * @return mixed
     */
    public function show($raceId)
    {
        $events = Event::where('race_id', $raceId)
            ->orderBy('event_name', 'ASC')
            ->get();
        return EventResource::collection($events);
    }

    /**
     * Ajoute les nouvelles classification associées à une course trouvées depuis l'API UCI
     * @param $raceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function addEvents($raceId)
    {
        $job = $this->dispatch(new AddEvents($raceId));
        return response()
            ->json([
                'jobs' => $job
            ]);
    }
}
