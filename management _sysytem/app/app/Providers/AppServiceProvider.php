<?php

namespace App\Providers;

use App\Candidacy;
use App\CandidacyHistory;
use App\Candidate;
use App\CodingChallenge;
use App\CodingSession;
use App\CodingSubmission;
use App\Feedback;
use App\Observers\CandidacyHistoryObserver;
use App\Observers\CandidacyObserver;
use App\Observers\CandidateObserver;
use App\Observers\CodingChallengeObserver;
use App\Observers\CodingSessionObserver;
use App\Observers\CodingSubmissionObserver;
use App\Observers\FeedbackObserver;
use App\Observers\QuestionnaireObserver;
use App\Observers\QuestionnaireSubmissionObserver;
use App\Observers\StageObserver;
use Illuminate\Support\ServiceProvider;
use App\Support\Events\EventSubscriber;
use App\Observers\UserObserver;
use App\Questionnaire;
use App\QuestionnaireSubmission;
use App\Stage;
use App\User;
use Event;

class AppServiceProvider extends ServiceProvider
{


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Event::subscribe(EventSubscriber::class);
        // Set the default serializer for Carbon json as Iso8601
        \Illuminate\Support\Carbon::serializeUsing(
            function ($carbon) {
                return $carbon->toIso8601String();
            }
        );

        User::observe(UserObserver::class);
        Stage::observe(StageObserver::class);
        Feedback::observe(FeedbackObserver::class);
        CodingSubmission::observe(CodingSubmissionObserver::class);
        CodingSession::observe(CodingSessionObserver::class);
        CodingChallenge::observe(CodingChallengeObserver::class);
        Candidate::observe(CandidateObserver::class);
        Candidacy::observe(CandidacyObserver::class);
        CandidacyHistory::observe(CandidacyHistoryObserver::class);
        Questionnaire::observe(QuestionnaireObserver::class);
        QuestionnaireSubmission::observe(QuestionnaireSubmissionObserver::class);
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
