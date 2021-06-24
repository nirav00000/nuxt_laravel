<?php

namespace App\Listeners;

use App\Events\CandidateRegistration;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendEmailJob;

class SendEmailToCandidateWhenRegistration
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




        $details['email'] = $event->candidate->email;
        $details['template'] = "email_to_candidate_while_registration";
        $details['subject'] = 'interview system from improwised technology';



        dispatch(new SendEmailJob($details));
    }
}
