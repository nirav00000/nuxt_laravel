<?php

namespace App\Listeners;

use App\Events\CloseStage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendEmailJob;
use App\User;

class SendEmailWhenStageClosed
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
     * @param  CloseStage  $event
     * @return void
     */
    public function handle(CloseStage $event)
    {

        $assigneeEmail = User::where('key', $event->history['metadata']['assignee_key'])->first()->email;
        $emails = [config('mail.hr'),$assigneeEmail];
        $arrayOfEmail = array_unique($emails);


        foreach ($arrayOfEmail as $email) {
            $details['email'] = $email;
            $details['template'] = 'email_when_stage_closed';
            $details['subject'] = 'stage is closed';
            dispatch(new SendEmailJob($details));
        }
    }
}
