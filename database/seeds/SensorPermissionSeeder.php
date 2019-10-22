<?php

use Illuminate\Database\Seeder;

class SensorPermissionSeeder extends Seeder
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
          'name' => 'read-sensor',
          'display_name' => 'Monitorear sensores',
          'description' => 'Permiso para leer estado actual de los sensores',
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
