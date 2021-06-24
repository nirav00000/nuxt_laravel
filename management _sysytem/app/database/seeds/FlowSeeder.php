<?php

use Illuminate\Database\Seeder;

class FlowSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Flow::class, 1)->create();
    }
}
