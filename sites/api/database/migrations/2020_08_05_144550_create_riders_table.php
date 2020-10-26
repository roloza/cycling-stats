<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riders', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pcm');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('custom-slug')->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('nationality')->nullable();
            $table->datetime('date_of_birth')->nullable();
            $table->integer('weight')->nullable();
            $table->integer('height')->nullable();
            $table->string('visual')->nullable();

            $table->string('current_team')->nullable();
            $table->string('uci_points')->nullable();
            $table->string('uci_position')->nullable();

            $table->timestamps();
        });

        Schema::create('pcm', function (Blueprint $table) {
            $table->id();
            $table->string('prenom');
            $table->string('nom');
            $table->string('slug')->unique();
            $table->string('region');
            $table->integer('taille');
            $table->integer('poids');
            $table->integer('popularite');
            $table->integer('potentiel');
            $table->datetime('dateDeNaissance');
            $table->integer('grimpeur');
            $table->integer('descendeur');
            $table->integer('puncheur');
            $table->integer('rouleur');
            $table->integer('gestionEffort');
            $table->integer('sprinter');
            $table->integer('panache');
            $table->integer('moyenne');

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
        Schema::dropIfExists('riders');
        Schema::dropIfExists('pcm');
    }
}
