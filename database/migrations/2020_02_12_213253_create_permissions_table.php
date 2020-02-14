<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('EtudiantID');
            $table->boolean('posting');
            $table->boolean('commenting');

            $table->foreign('EtudiantID')->references('id')->on('etudiants')->onDelete('cascade');
        });

        DB::table('permissions')->insert(
            array(
                'EtudiantID'=>"1",
                 'posting'=>true, 
                 'commenting'=>true,
            )
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
