<?php

namespace App\Observers;

use App\CodingChallenge;
use Vinkla\Hashids\Facades\Hashids;

class CodingChallengeObserver
{
    /**
     * Handle the coding challenge "created" event.
     *
     * @param  \App\CodingChallenge  $codingChallenge
     * @return void
     */
    public function created(CodingChallenge $codingChallenge)
    {
        $encoded_id = Hashids::encode($codingChallenge->id);
        $codingChallenge->key = $encoded_id;
        $codingChallenge->save();
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
