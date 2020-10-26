<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competition', function (Blueprint $table) {
            $table->id();
            $table->integer('competition_id');
            $table->integer('year');
            $table->string('name');
            $table->string('name_aff');
            $table->string('slug_aff');
            $table->string('slug');
            $table->datetime('start_at');
            $table->datetime('end_at');
            $table->string('country')->nullable();
            $table->boolean('is_in_progress');
            $table->boolean('is_done');
            $table->string('country_code')->nullable();
            $table->string('class_code');
            $table->string('date');
            $table->integer('count_stages');
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
        Schema::dropIfExists('competition');
    }
}
