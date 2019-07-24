<?php

use Illuminate\Database\Seeder;

class LimitPermissionSeeder extends Seeder
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
        'name' => 'read-limit',
        'display_name' => 'Leer límites',
        'description' => 'Leer límites ambientales enviados por los sensores',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ], [
        'name' => 'update-limit',
        'display_name' => 'Actualizar límites',
        'description' => 'Actualizar límites ambientales para cambio de estado',
        'created_at' => new \dateTime,
        'updated_at' => new \dateTime,
      ]
    ];

    App\Permission::insert($permissions);
  }
}
