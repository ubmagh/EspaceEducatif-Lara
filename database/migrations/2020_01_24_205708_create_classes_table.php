<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ClasseName', 50);
            $table->string('Filiere', 5);
            $table->string('Annee', 5);
            $table->string('ImagePath')->default('1600x400.png');
            $table->bigInteger('ProfID');


            $table->foreign('ProfID')->references('id')->on('professeurs')->onDelete('cascade');
        });
        DB::table('classes')->insert(
            array(
                'ClasseName' => 'Mathématiques : Analyse 2',
                'Filiere'=>'GI',
                'Annee' => '2',
                'ImagePath'=>'1600x400.png',
                'ProfID'=>'1'
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
        Schema::dropIfExists('classes');
    }
}
