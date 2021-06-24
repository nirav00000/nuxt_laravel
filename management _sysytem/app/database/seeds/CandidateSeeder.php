<?php

use Illuminate\Database\Seeder;

// use Database\factories\CandidateFactory;
// use App\Candidate;
class CandidateSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Candidate::class, 10)->create();
    }
}
