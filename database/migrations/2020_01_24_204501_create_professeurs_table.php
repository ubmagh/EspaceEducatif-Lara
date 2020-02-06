<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProfesseursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professeurs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Fname', 25);
            $table->string('Lname', 35);
            $table->string('email', 200);
            $table->string('Filiere', 5);
            $table->string('Sex', 5);
            $table->string('Matiere', 30);
            $table->string('AvatarPath');


            $table->foreign('email')->references('email')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        DB::table('professeurs')->insert(
            array(
                'Fname' => 'Omar',
                'Lname'=>'Zemzami',
                'email' => 'ubmagh2@gmail.com',
                'Filiere'=>'GI',
                'Sex'=>'M',
                'Matiere'=>'Mathématiques',
                'AvatarPath'=>'DefTM.png'
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
        Schema::dropIfExists('professeurs');
    }
}
