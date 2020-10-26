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
