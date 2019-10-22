<?php

use Illuminate\Database\Seeder;

class DevicePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $module = App\Module::whereName('Sensores')->first();

        if ($module) {
            $permissions = [
                [
                    'name' => 'read-device',
                    'display_name' => 'Leer dispositivo',
                    'description' => 'Permiso para leer datos de dispositivo',
                    'module_id' => $module->id
                ], [
                    'name' => 'update-device',
                    'display_name' => 'Actualizar dispositivo',
                    'description' => 'Permiso para actualizar datos de dispositivo',
                    'module_id' => $module->id
                ], [
                    'name' => 'create-device',
                    'display_name' => 'Crear dispositivo',
                    'description' => 'Permiso para insertar datos de dispositivo',
                    'module_id' => $module->id
                ], [
                    'name' => 'delete-device',
                    'display_name' => 'Eliminar dispositivo',
                    'description' => 'Permiso para eliminar datos de dispositivo',
                    'module_id' => $module->id
                ]
            ];

            foreach ($permissions as $permission) {
                App\Permission::firstOrCreate(
                    array_slice($permission, 0, 1),
                    array_slice($permission, 1)
                );
            }
        }
    }
}
