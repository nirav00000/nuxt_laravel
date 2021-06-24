<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodingSubmissionsTable extends Migration
{


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'coding_submissions',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('key')->unique();
                $table->bigInteger('session_id');
                $table->string('language');
                $table->text('code');
                $table->integer('total_tests');
                $table->integer('passed_tests');
                $table->json('result');
                $table->softDeletes();
                $table->timestamps();
                $table->foreign('session_id')->references('id')->on('coding_sessions')->onDelete('cascade');
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
        Schema::dropIfExists('coding_submissions');
    }
}
