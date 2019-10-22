<?php

use Illuminate\Database\Seeder;

class UserActionPermissionSeeder extends Seeder
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
          'name' => 'read-user-action',
          'display_name' => 'Leer acciones de usuarios',
          'description' => 'Permiso para leer datos de acciones de usuarios',
          'module_id' => $module->id
        ], [
          'name' => 'delete-user-action',
          'display_name' => 'Eliminar accion de usuario',
          'description' => 'Permiso para eliminar una accion de usuario',
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