<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use app\Flow;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create(
            'feedback',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->string('key', 7)->nullable()->unique();
                $table->foreign('user_id')->references('id')->on('users');
                $table->string('feedback');
                $table->string('description');
                $table->softDeletes();
                $table->timestamps();
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
        Schema::dropIfExists('feedback');
    }
}
