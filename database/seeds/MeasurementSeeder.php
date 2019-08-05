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
        $total_items = 10;

        for($i = $total_items; $i > 0; $i--) {
            factory(App\Measurement::class, $total_items)->create();
        }
    }
}
