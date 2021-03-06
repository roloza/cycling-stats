<?php

use App\Http\Controllers\RiderController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* riders */
Route::get('/riders', RiderController::class . '@index')->name('getRiders');
Route::get('/riders/{id}', RiderController::class . '@show')->name('getRiderById');
Route::post('/riders/add', RiderController::class . '@addRiders')->name('addRiders');
Route::post('/riders/add-pcm-riders', RiderController::class . '@addPcmRiders')->name('addPcmRiders');

/* Teams */
Route::get('/teams', TeamController::class . '@index')->name('getTeams');
Route::get('/teams/{id}', TeamController::class . '@show')->name('getTeamById');
Route::post('/teams/add', TeamController::class . '@addTeams')->name('addTeams');

/* Seasons */
Route::post('/seasons/add', \App\Http\Controllers\SeasonController::class . '@addSeasons')->name('addSeasons');

/* Competitions */
Route::get('/competitions', \App\Http\Controllers\CompetitionController::class . '@index')->name('getCompetitions');
Route::get('/competitions/{id}', \App\Http\Controllers\CompetitionController::class . '@show')->name('getCompetitionById');
Route::post('/competitions/add', \App\Http\Controllers\CompetitionController::class . '@addCompetitions')->name('addCompetitions');

/* Races */
Route::get('/races/{competitionId}', \App\Http\Controllers\RaceController::class . '@show')->name('getRaces');
Route::post('/races/add/{competitionId}', \App\Http\Controllers\RaceController::class . '@addRaces')->name('addRaces');

/* Events */
Route::get('/events/{raceId}', \App\Http\Controllers\EventController::class . '@show')->name('getEvents');
Route::post('/events/add/{raceId}', \App\Http\Controllers\EventController::class . '@addEvents')->name('addEvents');

/* Results */
Route::get('/results/{eventId}', \App\Http\Controllers\ResultController::class . '@show')->name('getResults');
Route::post('/results/add/{eventId}', \App\Http\Controllers\ResultController::class . '@addResults')->name('addResults');
