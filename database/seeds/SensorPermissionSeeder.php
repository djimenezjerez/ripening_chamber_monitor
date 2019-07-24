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
    $permissions = [
      [
        'name' => 'read-sensor',
        'display_name' => 'Monitorear sensores',
        'description' => 'Permiso para leer estado actual de los sensores',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ]
    ];

    App\Permission::insert($permissions);
  }
}
