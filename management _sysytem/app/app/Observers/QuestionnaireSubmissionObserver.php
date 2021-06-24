<?php

namespace App\Observers;

use App\CandidacyHistory;
use App\Questionnaire;
use App\QuestionnaireSubmission;
use App\Stage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

class QuestionnaireSubmissionObserver
{
    /**
     * Handle the questionnaire submission "created" event.
     *
     * @param  \App\QuestionnaireSubmission  $questionnaireSubmission
     * @return void
     */
    public function created(QuestionnaireSubmission $questionnaireSubmission)
    {
        $encoded_id = Hashids::encode($questionnaireSubmission->id);
        $questionnaireSubmission->key = $encoded_id;
        $questionnaireSubmission->save();
        //form can be submitted only if started...from candidacy metadata
        $questionnaire = Questionnaire::find($questionnaireSubmission->questionnaire_id);
        $stage = Stage::where('metadata->questionnaire_key', $questionnaire->key)->first();
        //*2* history new entry
        $default = [
            "candidacy_id" => $questionnaireSubmission->candidacy_id,
            "stage_name" => Str::snake($stage->name),
            "status" => "completed"
        ];
        $extra = [
            "metadata" => ["questionnaire_submission_key" => $questionnaireSubmission->key]
        ];
        CandidacyHistory::createFromData($default, null, ["metadata"], $extra);
        //*3* update candidacy metadata
        $questionnaireSubmission->candidacy->updateStageMetadata();
    }
    /**
     * Handle the questionnaire submission "updated" event.
     *
     * @param  \App\QuestionnaireSubmission  $questionnaireSubmission
     * @return void
     */
    public function updated(QuestionnaireSubmission $questionnaireSubmission)
    {
        //
    }
    /**
     * Handle the questionnaire submission "deleted" event.
     *
     * @param  \App\QuestionnaireSubmission  $questionnaireSubmission
     * @return void
     */
    public function deleted(QuestionnaireSubmission $questionnaireSubmission)
    {
        //
    }
    /**
     * Handle the questionnaire submission "restored" event.
     *
     * @param  \App\QuestionnaireSubmission  $questionnaireSubmission
     * @return void
     */
    public function restored(QuestionnaireSubmission $questionnaireSubmission)
    {
        //
    }
    /**
     * Handle the questionnaire submission "force deleted" event.
     *
     * @param  \App\QuestionnaireSubmission  $questionnaireSubmission
     * @return void
     */
    public function forceDeleted(QuestionnaireSubmission $questionnaireSubmission)
    {
        //
    }
}
