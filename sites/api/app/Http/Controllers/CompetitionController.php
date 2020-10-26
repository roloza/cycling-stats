<?php

namespace App\Http\Controllers;

use App\Jobs\AddCompetitions;
use App\Models\Competition;
use Illuminate\Http\Request;

/**
 * Class CompetitionController
 * @package App\Http\Controllers
 */
class CompetitionController extends Controller
{

    /**
     * Liste les compétitions
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $queryCompetition = Competition::query();
        $queryCompetition->orderBy('start_at','desc');

        /* Filtre par année */
        if(!is_null($request['year'])) {
            $queryCompetition->where('year','=', $request['year']);
        }

        /* Filtre par code pays */
        if(!is_null($request['country_code'])) {
            $queryCompetition->where('country_code','=', $request['country_code']);
        }

        /* Filtre par classification */
        if(!is_null($request['classification'])) {
            $queryCompetition->where('class_code','=', $request['classification']);
        }

        /* Filtre par nom */
        if(!is_null($request['name'])) {
            $queryCompetition->where('name','like','%' . $request['name'] . '%');
        }

        /* Filtre par nom affichage */
        if(!is_null($request['name_aff'])) {
            $queryCompetition->where('name_aff','like','%' . $request['name_aff'] . '%');
        }

        /* Filtre par slug */
        if(!is_null($request['slug'])) {
            $queryCompetition->where('slug','like','%' . $request['slug'] . '%');
        }

        /* Filtre par slug affichage */
        if(!is_null($request['slug_aff'])) {
            $queryCompetition->where('slug_aff','like','%' . $request['slug_aff'] . '%');
        }

        /* Filtre par pays */
        if(!is_null($request['country'])) {
            $queryCompetition->where('country','like','%' . $request['country'] . '%');
        }

        /* Filtres par date */
        if(!is_null($request['date_start_after'])) {
            $queryCompetition->whereDate('start_at','>=',$request['date_start_after']);
        }
        if(!is_null($request['date_start_before'])) {
            $queryCompetition->whereDate('start_at','<',$request['date_start_before']);
        }
        if(!is_null($request['date_end_after'])) {
            $queryCompetition->whereDate('end_at','>=',$request['date_end_after']);
        }
        if(!is_null($request['date_end_before'])) {
            $queryCompetition->whereDate('end_at','<',$request['date_end_before']);
        }

        /* Filtre par En cours */
        if(!is_null($request['isInProgress'])) {
            $queryCompetition->where('is_in_progress','=', $request['isInProgress']);
        }
        if(!is_null($request['isDone'])) {
            $queryCompetition->where('is_done','=', $request['isDone']);
        }


        return $queryCompetition->paginate(50);
    }

    /**
     * Affiche une compétition via son ID
     * @param $id
     * @return mixed
     */
    public function show($id) {
        return Competition::where('id', $id)->firstOrFail();
    }
    /**
     * Ajoute les nouvelles compétitions trouvées depuis l'API UCI
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCompetitions(Request $request)
    {
        $jobs = [];
        if (isset($request->year)) {
            $jobs[] = $this->dispatch(new AddCompetitions($request->year));
        } else {
            for ($year = 2020; $year >= 2005; $year--) {
                $jobs[] = $this->dispatch(new AddCompetitions($year));
            }
        }
        return response()
            ->json([
                'jobs' => $jobs
            ]);
    }
}
