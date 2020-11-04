<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResultResource;
use App\Jobs\AddResults;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Affiche un résultat de classification d'une course via son ID de classification
     * @param $eventId
     * @return mixed
     */
    public function show($eventId)
    {
        $result = Result::where('event_id', $eventId)
            ->orderBy('rank', 'ASC')
            ->get();
        return ResultResource::collection($result);
    }

    /**
     * Ajoute le nouveau résultat associé à un classement trouvés depuis l'API UCI
     * @param $eventId
     * @return \Illuminate\Http\JsonResponse
     */
    public function addResults($eventId)
    {
        $job = $this->dispatch(new AddResults($eventId));
        return response()
            ->json([
                'jobs' => $job
            ]);
    }
}
