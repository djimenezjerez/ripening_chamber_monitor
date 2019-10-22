<?php

use Illuminate\Database\Seeder;

class MagnitudePermissionSeeder extends Seeder
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
          'name' => 'read-magnitude',
          'display_name' => 'Leer magnitudes',
          'description' => 'Leer datos de magnitudes',
          'module_id' => $module->id
        ], [
          'name' => 'update-magnitude',
          'display_name' => 'Actualizar magnitudes',
          'description' => 'Actualizar datos de magnitudes',
          'module_id' => $module->id
        ], [
          'name' => 'create-magnitude',
          'display_name' => 'Crear magnitud',
          'description' => 'Insertar nueva de magnitud',
          'module_id' => $module->id
        ], [
          'name' => 'delete-magnitude',
          'display_name' => 'Eliminar magnitud',
          'description' => 'Eliminar datos de magnitudes',
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
