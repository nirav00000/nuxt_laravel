<?php

namespace App\Observers;

use App\CodingSubmission;
use Vinkla\Hashids\Facades\Hashids;

class CodingSubmissionObserver
{
    /**
     * Handle the coding submission "created" event.
     *
     * @param  \App\CodingSubmission  $codingSubmission
     * @return void
     */
    public function created(CodingSubmission $codingSubmission)
    {
        $encoded_id = Hashids::encode($codingSubmission->id);
        $codingSubmission->key = $encoded_id;
        $codingSubmission->save();
    }

    /**
     * Handle the coding submission "updated" event.
     *
     * @param  \App\CodingSubmission  $codingSubmission
     * @return void
     */
    public function updated(CodingSubmission $codingSubmission)
    {
        //
    }

    /**
     * Handle the coding submission "deleted" event.
     *
     * @param  \App\CodingSubmission  $codingSubmission
     * @return void
     */
    public function deleted(CodingSubmission $codingSubmission)
    {
        //
    }

    /**
     * Handle the coding submission "restored" event.
     *
     * @param  \App\CodingSubmission  $codingSubmission
     * @return void
     */
    public function restored(CodingSubmission $codingSubmission)
    {
        //
    }

    /**
     * Handle the coding submission "force deleted" event.
     *
     * @param  \App\CodingSubmission  $codingSubmission
     * @return void
     */
    public function forceDeleted(CodingSubmission $codingSubmission)
    {
        //
    }
}
