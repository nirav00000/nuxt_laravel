<?php

namespace App\Jobs\User;

use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var array
     */
    public $payload;


    /**
     * Create a new message instance.
     *
     * @param array $payload
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $to = $this->payload['email'];

        debug('SendWelcomeEmail->handle() - Sending email.', ['payload' => $this->payload, 'to' => $to]);
        $message = new WelcomeEmail($this->payload);

        // Send email
        Mail::to($to)->send($message);
    }
}
