<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffichagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affichages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp("date");
            $table->string('title',60);
            $table->text('content',150)->nullable();
            $table->bigInteger('classID');

            
            $table->foreign('classID')->references('id')->on('classes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affichages');
    }
}