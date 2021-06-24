<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CandidacyAddMetadataField extends Migration
{


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'candidacies',
            function (Blueprint $table) {
                $table->jsonb("metadata")->nullable();
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
        Schema::table(
            'candidacies',
            function (Blueprint $table) {
                $table->drop('metadata');
            }
        );
    }
}
