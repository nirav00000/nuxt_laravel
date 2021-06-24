<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CandidateTableStructureChange extends Migration
{


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'candidates',
            function (Blueprint $table) {
                $table->dropColumn(['contact_no', 'education', 'college', 'experience', 'last_company']);
            }
        );

        Schema::table(
            'candidates',
            function (Blueprint $table) {
                $table->json('metadata')->nullable()->after('email');
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
            'candidates',
            function (Blueprint $table) {
                $table->string('contact_no');
                $table->string('education');
                $table->string('college');
                $table->integer('experience');
                $table->string('last_company');
                $table->dropColumn('metadata');
            }
        );
    }
}
