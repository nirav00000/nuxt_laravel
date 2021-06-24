<?php

namespace App\Observers;

use App\Candidate;
use Vinkla\Hashids\Facades\Hashids;

class CandidateObserver
{
    /**
     * Handle the candidate "created" event.
     *
     * @param  \App\Candidate  $candidate
     * @return void
     */
    public function created(Candidate $candidate)
    {
        $encoded_id = Hashids::encode($candidate->id);
        $candidate->key = $encoded_id;
        $candidate->save();
    }

    /**
     * Handle the candidate "updated" event.
     *
     * @param  \App\Candidate  $candidate
     * @return void
     */
    public function updated(Candidate $candidate)
    {
        //
    }

    /**
     * Handle the candidate "deleted" event.
     *
     * @param  \App\Candidate  $candidate
     * @return void
     */
    public function deleted(Candidate $candidate)
    {
        //
    }

    /**
     * Handle the candidate "restored" event.
     *
     * @param  \App\Candidate  $candidate
     * @return void
     */
    public function restored(Candidate $candidate)
    {
        //
    }

    /**
     * Handle the candidate "force deleted" event.
     *
     * @param  \App\Candidate  $candidate
     * @return void
     */
    public function forceDeleted(Candidate $candidate)
    {
        //
    }
}
