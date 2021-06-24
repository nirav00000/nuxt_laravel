<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CandidacyHistoriesMetadataAndActorColumnChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("candidacy_histories", function (Blueprint $table) {
            $table->renameColumn("assignee","actor");
        });

        Schema::table("candidacy_histories", function (Blueprint $table) {
            $table->dropColumn("meta");
        });

        Schema::table("candidacy_histories", function (Blueprint $table) {
            $table->jsonb("metadata")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("candidacy_histories", function (Blueprint $table) {
            $table->renameColumn("actor","assignee");
        });

        Schema::table("candidacy_histories", function (Blueprint $table) {
            $table->jsonb("meta")->nullable();
        });

        Schema::table("candidacy_histories", function (Blueprint $table) {
            $table->drop("metadata");
        });
    }
}
