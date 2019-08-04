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
        $magnitudes = [
            [
                'name' => 'tem',
                'display_name' => 'Temperatura',
                'measure' => 'ºC',
                'created_at' => new \dateTime,
                'updated_at' => new \dateTime
            ], [
                'name' => 'hum',
                'display_name' => 'Humedad',
                'measure' => '%',
                'created_at' => new \dateTime,
                'updated_at' => new \dateTime
            ], [
                'name' => 'hic',
                'display_name' => 'Sensación térmica',
                'measure' => 'ºC',
                'created_at' => new \dateTime,
                'updated_at' => new \dateTime
            ]
        ];

        foreach($magnitudes as $magnitude) {
            App\Magnitude::create($magnitude);
        }
    }
}