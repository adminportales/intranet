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
            $table->string('title');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->string('guest');
            $table->string('engrave');
            $table->integer('chair_loan');
            $table->integer('proyector');
            $table->string('description');
            $table->string('reservation');
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