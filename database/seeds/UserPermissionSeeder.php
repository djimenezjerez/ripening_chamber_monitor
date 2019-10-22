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
    $module = App\Module::whereName('Administrador')->first();

    if ($module) {
      $permissions = [
        [
          'name' => 'read-user',
          'display_name' => 'Leer usuarios',
          'description' => 'Permiso para leer la lista de usuarios',
          'module_id' => $module->id
        ], [
          'name' => 'create-user',
          'display_name' => 'Crear usuario',
          'description' => 'Permiso para crear usuarios',
          'module_id' => $module->id
        ], [
          'name' => 'update-user',
          'display_name' => 'Actualizar usuario',
          'description' => 'Permiso para actualizar datos de usuarios',
          'module_id' => $module->id
        ], [
          'name' => 'delete-user',
          'display_name' => 'Eliminar usuario',
          'description' => 'Permiso para eliminar usuarios',
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