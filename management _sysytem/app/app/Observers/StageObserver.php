<?php

namespace App\Observers;

use App\Stage;
use Vinkla\Hashids\Facades\Hashids;

class StageObserver
{
    /**
     * Handle the stage "created" event.
     *
     * @param  \App\Stage  $stage
     * @return void
     */
    public function created(Stage $stage)
    {
        $encoded_id = Hashids::encode($stage->id);
        $stage->key = $encoded_id;
        $stage->save();
    }

    /**
     * Handle the stage "updated" event.
     *
     * @param  \App\Stage  $stage
     * @return void
     */
    public function updated(Stage $stage)
    {
        //
    }

    /**
     * Handle the stage "deleted" event.
     *
     * @param  \App\Stage  $stage
     * @return void
     */
    public function deleted(Stage $stage)
    {
        //
    }

    /**
     * Handle the stage "restored" event.
     *
     * @param  \App\Stage  $stage
     * @return void
     */
    public function restored(Stage $stage)
    {
        //
    }

    /**
     * Handle the stage "force deleted" event.
     *
     * @param  \App\Stage  $stage
     * @return void
     */
    public function forceDeleted(Stage $stage)
    {
        //
    }
}
