<?php

namespace App\Listeners;

use App\Events\CandidateRegistration;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendEmailJob;

class SendEmailToHrWhenRegistration
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
     * @param  CandidateRegistration  $event
     * @return void
     */
    public function handle(CandidateRegistration $event)
    {
        $hrEmail = config('mail.hr');

        $details = [
            "email" => $hrEmail,
            "template" => "email_to_hr_while_registration",
            "subject" => "new registration"
        ];



        dispatch(new SendEmailJob($details));
    }
}
