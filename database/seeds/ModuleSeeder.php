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
                'name' => 'Administrador',
                'created_at' => new \dateTime,
                'updated_at' => new \dateTime,
            ], [
                'name' => 'Sensores',
                'created_at' => new \dateTime,
                'updated_at' => new \dateTime,
            ], [
                'name' => 'Parámetros',
                'created_at' => new \dateTime,
                'updated_at' => new \dateTime,
            ]
        ];

        App\Module::insert($modules);
    }
}
