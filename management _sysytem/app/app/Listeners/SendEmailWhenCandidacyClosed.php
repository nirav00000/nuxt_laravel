<?php

namespace App\Listeners;

use App\Events\CloseCandidacy;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendEmailJob;

class SendEmailWhenCandidacyClosed
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CloseCandidacy  $event
     * @return void
     */
    public function handle(CloseCandidacy $event)
    {
        $hrEmail = config('mail.hr');

        $detailOfClosedCandidacy = [
            "email" => $hrEmail,
            "template" => "email_when_candidacy_closed",
            "subject" => "candidacy is closed"
        ];

        dispatch(new SendEmailJob($detailOfClosedCandidacy));
    }
}
