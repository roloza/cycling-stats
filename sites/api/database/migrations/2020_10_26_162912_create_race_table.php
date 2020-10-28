<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('race', function (Blueprint $table) {
            $table->id();
            $table->integer('competition_id')->index();
            $table->integer('race_id')->index();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('start_location')->nullable();
            $table->string('end_location')->nullable();
            $table->string('race_code')->nullable();
            $table->string('race_name')->nullable();
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
        Schema::dropIfExists('race');
    }
}
