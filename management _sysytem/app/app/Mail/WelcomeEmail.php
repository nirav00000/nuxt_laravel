<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
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
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject  = 'Welcome';
        $template = 'emails.welcome_email';

        return $this->subject($subject)->markdown($template)->with(['name' => $this->payload['name']]);
    }
}
