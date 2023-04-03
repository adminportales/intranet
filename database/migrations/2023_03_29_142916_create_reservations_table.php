<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('number_of_people');
            $table->integer('material');
            $table->integer('chair_loan');
            $table->string('description');
            $table->foreignId('id_usuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('id_sala')->references('id')->on('boardrooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
