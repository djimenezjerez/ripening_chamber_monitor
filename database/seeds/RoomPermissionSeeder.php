<?php

use Illuminate\Database\Seeder;

class RoomPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $module = App\Module::whereName('Parámetros')->first();

        if ($module) {
            $permissions = [
                [
                    'name' => 'read-room',
                    'display_name' => 'Leer ambiente',
                    'description' => 'Permiso para leer datos de ambiente',
                    'created_at' => new \dateTime,
                    'updated_at' => new \dateTime,
                    'module_id' => $module->id
                ], [
                    'name' => 'update-room',
                    'display_name' => 'Actualizar ambiente',
                    'description' => 'Permiso para actualizar datos de ambiente',
                    'created_at' => new \dateTime,
                    'updated_at' => new \dateTime,
                    'module_id' => $module->id
                ], [
                    'name' => 'create-room',
                    'display_name' => 'Crear ambiente',
                    'description' => 'Permiso para insertar datos de ambiente',
                    'created_at' => new \dateTime,
                    'updated_at' => new \dateTime,
                    'module_id' => $module->id
                ], [
                    'name' => 'delete-room',
                    'display_name' => 'Eliminar ambiente',
                    'description' => 'Permiso para eliminar datos de ambiente',
                    'created_at' => new \dateTime,
                    'updated_at' => new \dateTime,
                    'module_id' => $module->id
                ]
            ];

            App\Permission::insert($permissions);
        }
    }
}
