<?php

use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $total_items = 1;

        for($i = $total_items; $i > 0; $i--) {
            factory(App\Device::class, $total_items)->create();
        }
    }
}
