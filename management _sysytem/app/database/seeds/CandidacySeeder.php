<?php

use Illuminate\Database\Seeder;

class CandidacySeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Candidacy::class, 1)->create();
    }
}
