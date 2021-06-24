<?php

namespace App\Jobs;

use App\Exceptions\JobException;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Cache;
use App;
use Illuminate\Support\Arr;

class ProcessSNSMessages implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }


    /**
     * @param \Illuminate\Queue\Jobs\Job $job
     * @param array                      $data
     */
    public function handle(Job $job, array $data)
    {

        // Start the timer
        $startTime = microtime(true);

        debug('ProcessSNSMessages->handle() - Received payload.', ['payload' => $data]);

        try {
            // Sometimes we get messages that are in a wrong format
            // eg. When this job fails and gets requeued, the format is wrong and thus
            // the message is effectively lost.
            if (array_key_exists('Message', $data)) {
                $payload = json_decode(Arr::get($data, "Message", []), true);

                // Create Service
                $service = new App\Services\EventWorkflowService();
                $results = $service->process($payload);
                if ($results['success'] === true) {
                    info(
                        'ProcessSNSMessages->handle() - EventWorkflowService returned OK',
                        [
                            'event' => $payload['event'],
                        ]
                    );
                } else {
                    error(
                        'ProcessSNSMessages->handle() - EventWorkflowService returned an error',
                        [
                            'event'   => $payload['event'],
                            'error'   => $results['error'],
                            'payload' => $data,
                        ]
                    );
                }

                // Find System User
                $systemUser = Cache::rememberForever(
                    'system-user',
                    function () {
                        return App\User::whereEmail('system@ldex.co')->first();
                    }
                );
            } else {
                error(
                    'ProcessSNSMessages->handle() - Message was in incorrect format!',
                    [
                        'payload' => $data,
                    ]
                );
            }//end if
        } catch (\Exception $ex) {
            error(
                "ProcessSNSMessages->handle() threw an exception!!",
                [
                    'error'   => $ex->getMessage(),
                    'payload' => $data,
                ]
            );
        }//end try
    }
}
