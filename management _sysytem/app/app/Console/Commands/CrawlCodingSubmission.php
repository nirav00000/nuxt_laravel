<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CodingSubmission;
use Illuminate\Support\Facades\Log;

class CrawlCodingSubmission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:code {coding_submission_key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will run code against test cases';

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
        $coding_submission_key = $this->argument('coding_submission_key');

        // Check given coding submission exists
        $submission = CodingSubmission::where('key', $coding_submission_key)->first();

        if (!$submission) {
            Log::info("Invalid submission key!");
            return;
        }

        // Core part for RCE
        $language = $submission->language;
        $code = $submission->code;
        $total_tests = $submission->total_tests;
        $result = json_decode($submission->result);

        // Do not re-crawl submission if already crawled
        if ($result->crawled) {
            Log::info("Submission already crawled!");
        } else {
            // result inside results contains all test cases
            $results = $result->results;

            // last successful crawled test
            $last_result_crawled = $result->last_result_crawled + 1;


            $client = new \GuzzleHttp\Client(['base_uri' => env('RCE_ENDPOINT', 'http://localhost:5124')]);

            // Iterate test cases
            for ($s = $last_result_crawled; $s < $total_tests; $s += 1) {
                $res = $client->request('POST', '/run', [
                    'json' => [
                        'code' => $code,
                        'language' => $language,
                        'input' => $results[$s]->inputs,
                        'eo' => $results[$s]->output
                    ]
                ]);
                $res = json_decode($res->getBody()->getContents());

                // If passed (matches with exoected) then increment
                if ($res->matches) {
                    $submission->passed_tests += 1;
                }

                // Crawled all test cases if we reached at last
                if ($s === $total_tests - 1) {
                    $crawled = true;
                } else {
                    $crawled = false;
                }

                // Increment last crawled result
                $last_result_crawled = $result->last_result_crawled += 1;

                // Merge response from RCE with current test case
                $results[$s] = array_merge((array)$results[$s], (array) $res);

                // Update result field with updated array
                $submission->result = ['crawled' => $crawled, 'last_result_crawled' => $last_result_crawled, 'results' => $results];

                // Save submission
                $submission->save();
            }
        }
    }
}
