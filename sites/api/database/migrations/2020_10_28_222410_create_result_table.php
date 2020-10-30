<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result', function (Blueprint $table) {
            $table->id();

            $table->integer('event_id');
            $table->integer('result_id');
            $table->integer('rank');
            $table->string('name');
            $table->string('country')->nullable();
            $table->string('age')->nullable();
            $table->string('rider_id')->nullable();
            $table->string('value')->nullable();
            $table->string('country_name')->nullable();
            $table->string('iso_code')->nullable();
            $table->string('team_name')->nullable();
            $table->string('team_id')->nullable();
            $table->integer('point_pcr')->nullable();
            $table->string('retire')->nullable();

            $table->timestamps();

            $table->unique(['event_id', 'rank']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('result');
    }
}
