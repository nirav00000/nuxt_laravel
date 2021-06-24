<?php

namespace App\Listeners;

use App\Events\StageAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendEmailJob;
use App\Candidacy;
use App\Candidate;

class SendEmailToCandidateForStageAssignment
{
   /**
     * Create the event listener.
     *
     * @return void
     */



    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  CandidateRegistration  $event
     * @return void
     */
    public function handle(StageAssigned $event)
    {

        $candidateId = Candidacy::where('id', $event->history['candidacy_id'])->first()->candidate_id;
        $candidateEmail = Candidate::where('id', $candidateId)->first()->email;

        $details['email'] = $candidateEmail;
        $details['candidate'] = $event->history;
        $details['template'] = "email_to_candidate_at_stage_assignment";
        $details['subject'] = 'interview system from improwised technology';


        dispatch(new SendEmailJob($details));
    }
}
