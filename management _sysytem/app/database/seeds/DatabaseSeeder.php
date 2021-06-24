<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{


    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserTableSeeder::class);
        // $this->call(GroupsTableSeeder::class);
        // apicot
        $this->call(
            [
                FlowSeeder::class,
                CandidateSeeder::class,
                FeedbackSeeder::class,
                StageSeeder::class,
                CandidacySeeder::class,
                CandidacyHistorySeeder::class,
                QuestionnaireSeeder::class,
                QuestionnaireSubmissionSeeder::class,
            ]
        );
    }
}
