<?php

use Illuminate\Database\Seeder;

class QuestionnaireSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\QuestionnaireSubmission::class, 1)->create();
    }
}
