<?php

use Illuminate\Database\Seeder;

class MeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $total_items = 2;

		factory(App\Measurement::class, $total_items)->create();
    }
}
