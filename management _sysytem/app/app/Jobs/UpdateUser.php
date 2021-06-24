<?php

    namespace App\Jobs;

    use App;
    use App\Services\UserService;
    use App\User;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;

class UpdateUser implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     *
     * @var User
     */
    public $user;

        /**
         *
         * @var array
         */
    public $data;


        /**
         * Create a new job instance.
         *
         * @return void
         */
    public function __construct(User $user, array $data)
    {
        $this->user = $user;
        $this->data = $data;
    }


        /**
         * Execute the job.
         *
         * @param \App\Services\UserService $service
         */
    public function handle(UserService $service)
    {
        $results = $service->update($this->user, $this->data);
    }
}
