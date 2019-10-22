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
                'measure' => 'ºC'
            ], [
                'name' => 'hum',
                'display_name' => 'Humedad',
                'measure' => '%'
            ], [
                'name' => 'hic',
                'display_name' => 'Sensación térmica',
                'measure' => 'ºC'
            ]
        ];

        foreach($magnitudes as $magnitude) {
            App\Magnitude::firstOrCreate(
                array_slice($magnitude, 0, 1),
                array_slice($magnitude, 1)
            );
        }
    }
}