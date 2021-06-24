<?php

use Illuminate\Database\Seeder;
use App\Stage;

class StageSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Stage::class, 1)->create();
    }
}
