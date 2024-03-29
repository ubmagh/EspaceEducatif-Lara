<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;


class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email', 200)->unique();
            $table->string('password');
            $table->timestamp('LastLogin')->nullable();
            $table->timestamp('CreatedAt');
            $table->string('UserType', 20);
            $table->boolean('Activated');
        });

        DB::table('users')->insert(
            array(
                'email' => 'etudiant1@localhost.com',
                'password' => Hash::make('password'),
                'LastLogin'=>null,
                'CreatedAt'=>'2020/01/02',
                'UserType'=>'etud',
                'Activated'=>'1'
            )
        );



        DB::table('users')->insert(
            array(
                'email' => 'professeur1@localhost.com',
                'password' => Hash::make('password'),
                'LastLogin'=>null,
                'CreatedAt'=>'2020/01/02',
                'UserType'=>'prof',
                'Activated'=>'1'
            )
        );

        DB::table('users')->insert(
            array(
                'email' => 'admin@localhost.com',
                'password' => Hash::make('password'),
                'LastLogin'=>null,
                'CreatedAt'=>'2020/01/02',
                'UserType'=>'admin',
                'Activated'=>'1'
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
        Schema::dropIfExists('users');
    }
}