<?php

namespace App\Http\Controllers;

use App\Jobs\AddRaces;
use App\Models\Race;
use Illuminate\Http\Request;

class RaceController extends Controller
{

    /**
     * Affiche une course via son ID competition
     * @param $competitionId
     * @return mixed
     */
    public function show($competitionId) {
        return Race::where('competition_id', $competitionId)
            ->orderBy('start_at', 'DESC')
            ->get();
    }

    /**
     *  Ajoute les nouvelles courses trouvées depuis l'API UCI
     * @param Request $request
     * @param $competitionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function addRaces(Request $request, $competitionId)
    {
        $job = $this->dispatch(new AddRaces($competitionId));
        return response()
            ->json([
                'jobs' => $job
            ]);
    }
}
