<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('shortname');
            $table->string('slug')->unique();
            $table->string('category');
            $table->string('code');
            $table->string('continent_code');
            $table->string('country_code');
            $table->string('country_code_iso');
            $table->string('discipline_code');
            $table->string('website')->nullable();
            $table->integer('season_year');
            $table->integer('team_history_id');
            $table->integer('team_name_id');
            $table->integer('team_order');
            $table->integer('team_parent_id');
            $table->integer('team_season_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }
}
