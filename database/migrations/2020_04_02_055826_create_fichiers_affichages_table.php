<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFichiersAffichagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fichiers_affichages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('AffichageID')->unsigned();
            $table->bigInteger('MediaID')->unsigned();


            $table->foreign('AffichageID')->references('id')->on('affichages')->onDelete('cascade');
            $table->foreign('MediaID')->references('id')->on('media');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fichiers_affichages');
    }
}