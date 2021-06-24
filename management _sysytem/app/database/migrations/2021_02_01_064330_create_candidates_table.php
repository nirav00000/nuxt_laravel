<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidatesTable extends Migration
{


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'candidates',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('key', 10)->nullable()->unique();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('contact_no');
                $table->string('education');
                $table->string('college');
                $table->integer('experience');
                $table->string('last_company');
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidates');
    }
}
