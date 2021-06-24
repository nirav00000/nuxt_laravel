<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CandidateTableFieldsNullable extends Migration
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
                $table->string('key')->nullable(false)->change();
                $table->string('contact_no')->nullable()->change();
                $table->string('education')->nullable()->change();
                $table->string('college')->nullable()->change();
                $table->integer('experience')->nullable()->change();
                $table->string('last_company')->nullable()->change();
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
                $table->string('key')->nullable()->change();
                $table->string('contact_no')->change();
                $table->string('education')->change();
                $table->string('college')->change();
                $table->integer('experience')->change();
                $table->string('last_company')->change();
            }
        );
    }
}
