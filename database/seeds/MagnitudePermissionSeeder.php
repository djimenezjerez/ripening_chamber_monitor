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
          'created_at' => new \dateTime,
          'updated_at' => new \dateTime,
          'module_id' => $module->id
        ], [
          'name' => 'update-magnitude',
          'display_name' => 'Actualizar magnitudes',
          'description' => 'Actualizar datos de magnitudes',
          'created_at' => new \dateTime,
          'updated_at' => new \dateTime,
          'module_id' => $module->id
        ], [
          'name' => 'create-magnitude',
          'display_name' => 'Crear magnitud',
          'description' => 'Insertar nueva de magnitud',
          'created_at' => new \dateTime,
          'updated_at' => new \dateTime,
          'module_id' => $module->id
        ], [
          'name' => 'delete-magnitude',
          'display_name' => 'Eliminar magnitud',
          'description' => 'Eliminar datos de magnitudes',
          'created_at' => new \dateTime,
          'updated_at' => new \dateTime,
          'module_id' => $module->id
        ]
      ];

      App\Permission::insert($permissions);
    }
  }
}
