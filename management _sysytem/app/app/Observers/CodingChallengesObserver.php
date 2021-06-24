<?php

namespace App\Observers;

use App\CodingChallenge;
use Vinkla\Hashids\Facades\Hashids;

class CodingChallengesObserver
{
    /**
     * Handle the coding challenge "created" event.
     *
     * @param  \App\CodingChallenge  $codingChallenge
     * @return void
     */
    public function created(CodingChallenge $coding_challenge)
    {
        $encoded_id = Hashids::encode($coding_challenge->id);
        $coding_challenge->key = $encoded_id;
        $coding_challenge->save();
    }

    /**
     * Handle the coding challenge "updated" event.
     *
     * @param  \App\CodingChallenge  $codingChallenge
     * @return void
     */
    public function updated(CodingChallenge $codingChallenge)
    {
        //
    }

    /**
     * Handle the coding challenge "deleted" event.
     *
     * @param  \App\CodingChallenge  $codingChallenge
     * @return void
     */
    public function deleted(CodingChallenge $codingChallenge)
    {
        //
    }

    /**
     * Handle the coding challenge "restored" event.
     *
     * @param  \App\CodingChallenge  $codingChallenge
     * @return void
     */
    public function restored(CodingChallenge $codingChallenge)
    {
        //
    }

    /**
     * Handle the coding challenge "force deleted" event.
     *
     * @param  \App\CodingChallenge  $codingChallenge
     * @return void
     */
    public function forceDeleted(CodingChallenge $codingChallenge)
    {
        //
    }
}
