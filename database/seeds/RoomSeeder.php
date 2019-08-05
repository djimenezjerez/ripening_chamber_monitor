<?php

use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $total_items = 3;

        for($i = $total_items; $i > 0; $i--) {
            $room = factory(App\Room::class)->create();
        }
    }
}
