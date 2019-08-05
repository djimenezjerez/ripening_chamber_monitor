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
        $total_items = 200;

        App\Measurement::insert(factory(App\Measurement::class, $total_items)->make()->toArray());
    }
}
