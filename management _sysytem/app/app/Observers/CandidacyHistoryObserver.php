<?php

namespace App\Observers;

use App\CandidacyHistory;
use Vinkla\Hashids\Facades\Hashids;

class CandidacyHistoryObserver
{
    /**
     * Handle the candidacy history "created" event.
     *
     * @param  \App\CandidacyHistory  $candidacyHistory
     * @return void
     */
    public function created(CandidacyHistory $candidacyHistory)
    {
        $encoded_id = Hashids::encode($candidacyHistory->id);
        $candidacyHistory->key = $encoded_id;
        $candidacyHistory->save();
    }

    /**
     * Handle the candidacy history "updated" event.
     *
     * @param  \App\CandidacyHistory  $candidacyHistory
     * @return void
     */
    public function updated(CandidacyHistory $candidacyHistory)
    {
        //
    }

    /**
     * Handle the candidacy history "deleted" event.
     *
     * @param  \App\CandidacyHistory  $candidacyHistory
     * @return void
     */
    public function deleted(CandidacyHistory $candidacyHistory)
    {
        //
    }

    /**
     * Handle the candidacy history "restored" event.
     *
     * @param  \App\CandidacyHistory  $candidacyHistory
     * @return void
     */
    public function restored(CandidacyHistory $candidacyHistory)
    {
        //
    }

    /**
     * Handle the candidacy history "force deleted" event.
     *
     * @param  \App\CandidacyHistory  $candidacyHistory
     * @return void
     */
    public function forceDeleted(CandidacyHistory $candidacyHistory)
    {
        //
    }
}
