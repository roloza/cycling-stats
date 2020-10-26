<?php

namespace App\Http\Controllers;

use App\Jobs\GetTeam;
use App\Team;
use Illuminate\Http\Request;

/**
 * Class TeamController
 * @package App\Http\Controllers
 */
class TeamController extends Controller
{


    /**
     * Liste les équipes
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $queryTeam = Team::query();
        $queryTeam->orderBy('season_year','desc');

        /* Filtre par année */
        if(!is_null($request['year'])) {
            $queryTeam->where('season_year','=',$request['year']);
        }

        /* Filtre par pays */
        if(!is_null($request['country'])) {
            $queryTeam->where('country_code_iso','=',$request['country']);
        }

        /* Filtre par region */
        if(!is_null($request['region'])) {
            $queryTeam->where('continent_code','=',$request['region']);
        }

        /* Filtre par niveau */
        if(!is_null($request['code'])) {
            $queryTeam->where('code','=',$request['code']);
        }

        /* Filtre par shortname */
        if(!is_null($request['shortname'])) {
            $queryTeam->where('shortname','=',$request['shortname']);
        }

        /* Filtre par nom */
        if(!is_null($request['name'])) {
            $queryTeam->where('fullname','like','%' . $request['name'] . '%');
        }

        return $queryTeam->paginate(50);
    }

    /**
     * Affiche une équipe via son ID
     * @param $id
     * @return mixed
     */
    public function show($id) {
        return Team::where('id', $id)->firstOrFail();
    }

    /**
     * Ajoute les nouvelles équipes trouvées depuis l'API UCI
     * @param Request $request
     */
    public function addTeams(Request $request)
    {
        $jobs = [];
        if (isset($request->year)) {
            $jobs[] = $this->dispatch(new GetTeam($request->year));
        } else {
            for ($year = 2020; $year >= 2005; $year--) {
                $jobs[] = $this->dispatch(new GetTeam($year));
            }
        }
        return response()
            ->json([
                'jobs' => $jobs
            ]);
    }
}
