<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidacyHistoriesTable extends Migration
{


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'candidacy_histories',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('key')->unique();
                $table->unsignedBigInteger('candidacy_id');
                $table->foreign('candidacy_id')->references('id')->on('candidacies')->onDelete('cascade');
                $table->string('stage_name')->nullable();
                $table->string('assignee')->nullable();
                $table->string('status')->nullable();
                $table->jsonb('meta')->nuallable();
                $table->timestamp('created_at');
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
        Schema::dropIfExists('candidacy_histories');
    }
}
