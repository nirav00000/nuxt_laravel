<?php

use Illuminate\Database\Seeder;

class CandidacyHistorySeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\CandidacyHistory::class, 1)->create();
    }

}
