<?php

use Illuminate\Database\Seeder;

class MagnitudeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $total_items = 2;

		factory(App\Magnitude::class, $total_items)->create();
    }
}
