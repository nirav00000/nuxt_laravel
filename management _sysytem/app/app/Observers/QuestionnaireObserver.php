<?php

namespace App\Observers;

use App\Questionnaire;
use Vinkla\Hashids\Facades\Hashids;

class QuestionnaireObserver
{
    /**
     * Handle the questionnaire "created" event.
     *
     * @param  \App\Questionnaire  $questionnaire
     * @return void
     */
    public function created(Questionnaire $questionnaire)
    {
        $encoded_id = Hashids::encode($questionnaire->id);
        $questionnaire->key = $encoded_id;
        $questionnaire->save();
    }

    /**
     * Handle the questionnaire "updated" event.
     *
     * @param  \App\Questionnaire  $questionnaire
     * @return void
     */
    public function updated(Questionnaire $questionnaire)
    {
        //
    }

    /**
     * Handle the questionnaire "deleted" event.
     *
     * @param  \App\Questionnaire  $questionnaire
     * @return void
     */
    public function deleted(Questionnaire $questionnaire)
    {
        //
    }

    /**
     * Handle the questionnaire "restored" event.
     *
     * @param  \App\Questionnaire  $questionnaire
     * @return void
     */
    public function restored(Questionnaire $questionnaire)
    {
        //
    }

    /**
     * Handle the questionnaire "force deleted" event.
     *
     * @param  \App\Questionnaire  $questionnaire
     * @return void
     */
    public function forceDeleted(Questionnaire $questionnaire)
    {
        //
    }
}
