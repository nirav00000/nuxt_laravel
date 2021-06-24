<?php

use Illuminate\Database\Migrations\Migration;

class AddSystemUser extends Migration
{


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\User::create(
            [

                'name'              => 'example',
                'email'             => 'example@improwised.com',
                'email_verified_at' => now(),
                'password'          => bcrypt('secretxxx123'),
            ]
        );
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
