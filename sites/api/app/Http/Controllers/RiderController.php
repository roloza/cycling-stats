<?php

namespace App\Http\Controllers;

use App\Jobs\AddPcmRiders;
use App\Jobs\GetTeamRiders;
use App\Rider;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;


/**
 * Class RiderController
 * @package App\Http\Controllers
 */
class RiderController extends Controller
{


    /**
     * Liste les coureurs
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $queryRider = Rider::query()->with('pcm');
        $queryRider->orderBy('slug','asc');

        /* Filtre par nom complet */
        if(!is_null($request['fullname'])) {
            $queryRider->where('name','like','%' . $request['fullname'] . '%');
        }
        /* Filtre par prenom */
        if(!is_null($request['firstname'])) {
            $queryRider->where('firstname','like','%' . $request['firstname'] . '%');
        }

        /* Filtre par nom */
        if(!is_null($request['lastname'])) {
            $queryRider->where('lastname','like','%' . $request['lastname'] . '%');
        }

        /* Filtre par pays */
        if(!is_null($request['country'])) {
            $queryRider->where('nationality','=',$request['country']);
        }

        return $queryRider->paginate(50);
    }

    /**
     * Affiche un coureur via son ID
     * @param $id
     * @return mixed
     */
    public function show($id) {
        return Rider::where('id', $id)->with('pcm')->firstOrFail();
    }

    /**
     * Ajoute les nouveaux coureurs via les teams
     */
    public function addRiders()
    {
        $jobs = [];
        $teams = Team::get();
        foreach ($teams as $team) {
            Log::debug("Equipe : " . $team->shortname . ' (' . $team->season_year . ')');
            $jobs[] = $this->dispatch(new GetTeamRiders($team));
        }

        return response()
            ->json([
                'jobs' => $jobs
            ]);
    }

    /**
     * Ajoute les coureurs depuis PCM (pro cycling manager)
     */
    public function addPcmRiders()
    {
        $jobs = [];
        $jobs[] = $this->dispatch(new AddPcmRiders());

        return response()
            ->json([
                'jobs' => $jobs
            ]);
    }
}
