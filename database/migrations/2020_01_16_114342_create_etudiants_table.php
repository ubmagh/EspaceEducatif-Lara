<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtudiantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etudiants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Fname', 25);
            $table->string('Lname', 35);
            $table->string('email', 200);
            $table->string('Filiere', 5);
            $table->string('Sex', 5);
            $table->string('Annee', 5);
            $table->string('CIN', 12);
            $table->string('AvatarPath');


            $table->foreign('email')->references('email')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('etudiants');
    }
}
