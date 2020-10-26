<?php

namespace App\Http\Controllers;

use App\Jobs\AddSeasons;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    public function addSeasons()
    {
        $jobs = [];
        $jobs[] = $this->dispatch(new AddSeasons());

        return response()
            ->json([
                'jobs' => $jobs
            ]);
    }
}
