<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodingSessionsTable extends Migration
{


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'coding_sessions',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('key')->unique();
                $table->bigInteger('challenge_id')->nullable();
                $table->bigInteger('candidacy_id');
                $table->text('code');
                $table->string('language');
                $table->softDeletes();
                $table->timestamps();
                $table->foreign('challenge_id')->references('id')->on('coding_challenges')->onDelete('set null');
                $table->foreign('candidacy_id')->references('id')->on('candidacies')->onDelete('cascade');
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
        Schema::dropIfExists('coding_sessions');
    }
}
