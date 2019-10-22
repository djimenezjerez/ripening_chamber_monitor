<?php

use Illuminate\Database\Seeder;

class MagnitudeRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $magnitudes = App\Magnitude::get();
        $rooms = App\Room::get();

        foreach($rooms as $room) {
            foreach($magnitudes as $magnitude) {
                $room->magnitudes()->syncWithoutDetaching([$magnitude->id =>
                    [
                        'min_limit' => 10,
                        'max_limit' => 60,
                        'interval' => 5
                    ]
                ]);
            }
        }
    }
}
