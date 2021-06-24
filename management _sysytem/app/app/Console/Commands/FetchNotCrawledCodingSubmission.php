<?php

namespace App\Console\Commands;

use App\Candidacy;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\CandidacyHistory;
use Illuminate\Support\Facades\DB;
use App\CodingSession;
use App\CodingSubmission;
use App\CodingChallenge;
use Illuminate\Support\Facades\Log;

date_default_timezone_set("UTC");

class FetchNotCrawledCodingSubmission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'code:submissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command submits fetch coding submissions whose started > 30 minutes and not submitted yet!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Left Join coding sessions and coding submissions
        $sessions = DB::table('coding_sessions')->whereNull('submitted_at')->where('started_at', '<', Carbon::now()->subMinutes(env('CODING_SESSION_EXPIRY_TIME', 30))->toDateTimeString())->get();

        // All sessions are found in submissions
        if (count($sessions) <= 0) {
            Log::info("All good!");
            return;
        } else {
            // Take session id and see started time in candidacy history
            foreach ($sessions as $si) {
                $started_history = CandidacyHistory::where(['metadata->session_key' => $si->key, 'status' => 'started'])->get();

                // Take current coding session
                $session = CodingSession::where('key', $si->key)->first();
                $challenge = CodingChallenge::where('id', $session->challenge_id)->first();
                $tests = json_decode($challenge->tests);

                // Creating submission
                $submission = CodingSubmission::create([
                'session_id' => $session->id,
                    'language' => $session->language ? $session->language : 'python',
                    'code' => $session->code ? $session->code : 'Not written by user but writen by system due to empty code - AABBCC',
                    'total_tests' => count($tests),
                    'passed_tests' => 0,
                    'result' => json_encode([
                        'crawled' => false,
                        'last_result_crawled' => -1,
                        'results' => $tests
                    ])
                ]);

                    // Make submission entry in coding session
                $session->submitted_at = now('UTC');
                $session->save();

                // Entry in history
                CandidacyHistory::create([
                    'candidacy_id' => $session->candidacy_id,
                    'stage_name' =>  $started_history[0]->stage_name,
                    'actor' => $started_history[0]->actor,
                    'status' => 'completed',
                    'metadata' => ['session_key' => $session->key, 'submission_key' => $submission->key, 'assignee_key' => $started_history[0]["metadata"]["assignee_key"]]
                ]);

                // Update candidacy metadata
                Candidacy::find($session->candidacy_id)->updateStageMetadata();

                $this->call("crawl:code", [
                    'coding_submission_key' => $submission->key
                ]);
            }
        }
    }
}
