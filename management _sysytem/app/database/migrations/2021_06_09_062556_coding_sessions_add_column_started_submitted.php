<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CodingSessionsAddColumnStartedSubmitted extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coding_sessions', function (Blueprint $table) {
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coding_sessions', function (Blueprint $table) {
            $table->dropColumn('started_at');
            $table->dropColumn('submitted_at');
        });
    }
}
