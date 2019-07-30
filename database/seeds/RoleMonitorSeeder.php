<?php

use Illuminate\Database\Seeder;

class RoleMonitorSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $role = App\Role::firstOrCreate([
      'name' => 'monitor',
      'display_name' => 'MONITOR'
    ]);

    $permisions = App\Permission::where('name', 'read-sensor')->orWhere('name', 'read-limit')->get()->toArray();

    $role->attachPermissions($permisions);
  }
}