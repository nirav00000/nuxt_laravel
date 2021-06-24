<?php

namespace App\Observers;

use App\CodingSession;
use Vinkla\Hashids\Facades\Hashids;

class CodingSessionObserver
{
    /**
     * Handle the coding session "created" event.
     *
     * @param  \App\CodingSession  $codingSession
     * @return void
     */
    public function created(CodingSession $codingSession)
    {
        $encoded_id = Hashids::encode($codingSession->id);
        $codingSession->key = $encoded_id;
        $codingSession->save();
    }

    /**
     * Handle the coding session "updated" event.
     *
     * @param  \App\CodingSession  $codingSession
     * @return void
     */
    public function updated(CodingSession $codingSession)
    {
        //
    }

    /**
     * Handle the coding session "deleted" event.
     *
     * @param  \App\CodingSession  $codingSession
     * @return void
     */
    public function deleted(CodingSession $codingSession)
    {
        //
    }

    /**
     * Handle the coding session "restored" event.
     *
     * @param  \App\CodingSession  $codingSession
     * @return void
     */
    public function restored(CodingSession $codingSession)
    {
        //
    }

    /**
     * Handle the coding session "force deleted" event.
     *
     * @param  \App\CodingSession  $codingSession
     * @return void
     */
    public function forceDeleted(CodingSession $codingSession)
    {
        //
    }
}
