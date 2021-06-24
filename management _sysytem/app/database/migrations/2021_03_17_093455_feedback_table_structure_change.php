<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Feedback;

class FeedbackTableStructureChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create(
        //     'newfeedback',
        //     function (Blueprint $table) {
        //         $table->bigIncrements('id');
        //         $table->string('key')->unique();
        //         $table->unsignedBigInteger('user_id');
        //         $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        //         $table->string('candidacy_id');
        //         $table->foreign('candidacy_id')->references('id')->on('candidacies')->onDelete('cascade');
        //         $table->string('verdict');
        //         $table->string('description');
        //         $table->softDeletes();
        //         $table->timestamps();
        //     }
        // );
        // Schema::table(
        //     'newfeedback',
        //     function (Blueprint $table) {
        //         $feedback = Feedback::all();

        //         foreach ($feedback as $feedback) {
        //             DB::table('newfeedback')->insert(
        //                 [
        //                  'user_id'      =>$feedback->user_id,
        //                  'key'          =>$feedback->key,
        //                  'candidacy_id' => Feedback::all()->random()->id,
        //                  'verdict'      =>$feedback->feedback,
        //                  'description'  =>$feedback->description,
        //                  'deleted_at'   =>$feedback->deleted_at,
        //                  'created_at'   => $feedback->created_at,
        //                  'updated_at'   => $feedback->updated_at
        //                 ]
        //             );
                   
        //         }
           
        //     }
        // );
        // Schema::dropIfExists('feedback');

        // Schema::rename('newfeedback', 'feedback');

        // Schema::table(
        //     'feedback',
        //     function (Blueprint $table) {
        //         $table->string('candidacy_id')->nullable(false)->change()->after('key');
        //     }
        // );
        Schema::table(
            'feedback',
            function (Blueprint $table) {
                $table->unsignedBigInteger('candidacy_id')->after('user_id');
                $table->foreign('candidacy_id')->references('id')->on('candidacies')->onDelete('cascade');
            }
        );

        Schema::table(
            'feedback',
            function (Blueprint $table) {
                $table->string('key')->nullable(false)->change();
                $table->renameColumn('feedback','verdict');
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
            'feedback',
            function (Blueprint $table) {
                $table->dropForeign('feedback_candidacy_id_foreign');
                $table->dropColumn('candidacy_id');
            }
        );

        Schema::table(
            'feedback',
            function (Blueprint $table) {
                $table->string('key')->nullable()->change();
                $table->renameColumn('verdict','feedback');
            }
        );
    }
}
