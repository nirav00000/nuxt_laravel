<?php

namespace App\Listeners;

use App\Events\StageAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendEmailJob;
use App\User;

class SendEmailToAssigneeForStageAssignment
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

        $assigneeEmail = User::where('key', $event->history['metadata']['assignee_key'])->first()->email;

        $details['email'] = $assigneeEmail;
        $details['assignee'] = $event->history;
        $details['template'] = 'email_to_assignee_at_stage_assign';
        $details['subject'] = 'your allocation in our interview system';

        dispatch(new SendEmailJob($details));
    }
}
