<?php

use Illuminate\Database\Seeder;

class UserPermissionSeeder extends Seeder
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
          'name' => 'read-user',
          'display_name' => 'Leer usuarios',
          'description' => 'Permiso para leer la lista de usuarios',
          'created_at' => new \dateTime,
          'updated_at' => new \dateTime,
          'module_id' => $module->id
        ], [
          'name' => 'create-user',
          'display_name' => 'Crear usuario',
          'description' => 'Permiso para crear usuarios',
          'created_at' => new \dateTime,
          'updated_at' => new \dateTime,
          'module_id' => $module->id
        ], [
          'name' => 'update-user',
          'display_name' => 'Actualizar usuario',
          'description' => 'Permiso para actualizar datos de usuarios',
          'created_at' => new \dateTime,
          'updated_at' => new \dateTime,
          'module_id' => $module->id
        ], [
          'name' => 'delete-user',
          'display_name' => 'Eliminar usuario',
          'description' => 'Permiso para eliminar usuarios',
          'created_at' => new \dateTime,
          'updated_at' => new \dateTime,
          'module_id' => $module->id
        ]
      ];

      App\Permission::insert($permissions);
    }
  }
}