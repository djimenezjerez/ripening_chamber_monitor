<?php

use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = [
            [
                'name' => 'Administrador'
            ], [
                'name' => 'Sensores'
            ], [
                'name' => 'Parámetros'
            ]
        ];

        foreach ($modules as $module) {
            App\Module::firstOrCreate($module);
        }
    }
}
