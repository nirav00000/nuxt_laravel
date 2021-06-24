<?php

namespace App\Observers;

use App\Feedback;
use Vinkla\Hashids\Facades\Hashids;

class FeedbackObserver
{
    /**
     * Handle the feedback "created" event.
     *
     * @param  \App\Feedback  $feedback
     * @return void
     */
    public function created(Feedback $feedback)
    {
        $encoded_id = Hashids::encode($feedback->id);
        $feedback->key = $encoded_id;
        $feedback->save();
    }

    /**
     * Handle the feedback "updated" event.
     *
     * @param  \App\Feedback  $feedback
     * @return void
     */
    public function updated(Feedback $feedback)
    {
        //
    }

    /**
     * Handle the feedback "deleted" event.
     *
     * @param  \App\Feedback  $feedback
     * @return void
     */
    public function deleted(Feedback $feedback)
    {
        //
    }

    /**
     * Handle the feedback "restored" event.
     *
     * @param  \App\Feedback  $feedback
     * @return void
     */
    public function restored(Feedback $feedback)
    {
        //
    }

    /**
     * Handle the feedback "force deleted" event.
     *
     * @param  \App\Feedback  $feedback
     * @return void
     */
    public function forceDeleted(Feedback $feedback)
    {
        //
    }
}
