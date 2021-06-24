<?php

namespace App\Observers;

use App\Candidacy;
use Vinkla\Hashids\Facades\Hashids;

class CandidacyObserver
{
    /**
     * Handle the candidacy "created" event.
     *
     * @param  \App\Candidacy  $candidacy
     * @return void
     */
    public function created(Candidacy $candidacy)
    {
        $encoded_id = Hashids::encode($candidacy->id);
        $candidacy->key = $encoded_id;
        $candidacy->save();
    }

    /**
     * Handle the candidacy "updated" event.
     *
     * @param  \App\Candidacy  $candidacy
     * @return void
     */
    public function updated(Candidacy $candidacy)
    {
        //
    }

    /**
     * Handle the candidacy "deleted" event.
     *
     * @param  \App\Candidacy  $candidacy
     * @return void
     */
    public function deleted(Candidacy $candidacy)
    {
        //
    }

    /**
     * Handle the candidacy "restored" event.
     *
     * @param  \App\Candidacy  $candidacy
     * @return void
     */
    public function restored(Candidacy $candidacy)
    {
        //
    }

    /**
     * Handle the candidacy "force deleted" event.
     *
     * @param  \App\Candidacy  $candidacy
     * @return void
     */
    public function forceDeleted(Candidacy $candidacy)
    {
        //
    }
}
