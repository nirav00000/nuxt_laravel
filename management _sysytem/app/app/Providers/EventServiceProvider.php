<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\CodingChallenge;
use App\Observers\CodingChallengesObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // User
        'App\Events\StageAssigned' => [
            'App\Listeners\SendEmailToCandidateForStageAssignment',
            'App\Listeners\SendEmailToAssigneeForStageAssignment',
        ],

        'App\Events\CandidateRegistration' => [
            'App\Listeners\SendEmailToCandidateWhenRegistration',
            'App\Listeners\SendEmailToHrWhenRegistration',

        ],

        'App\Events\CloseStage' => [
            'App\Listeners\SendEmailWhenStageClosed',
        ],

        'App\Events\CloseCandidacy' => [
            'App\Listeners\SendEmailWhenCandidacyClosed',
        ],
    ];



    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        CodingChallenge::observe(CodingChallengesObserver::class);
    }
}
