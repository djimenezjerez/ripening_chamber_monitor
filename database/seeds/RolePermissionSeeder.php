<?php

use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
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
          'name' => 'read-role',
          'display_name' => 'Leer roles',
          'description' => 'Permiso para leer la lista de roles',
          'module_id' => $module->id
        ], [
          'name' => 'update-role',
          'display_name' => 'Actualizar rol',
          'description' => 'Permiso para actualizar datos de rol',
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