<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('date');
            $table->bigInteger('PostID')->nullable();
            $table->bigInteger('PosterID');
            $table->string('type');
            $table->string('path');/// contains name and extension of stored file
            $table->string('originalName');// Name to return to user in Download 
            $table->integer('size');

            $table->foreign('PosterID')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('PostID')->references('id')->on('posts')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
}
