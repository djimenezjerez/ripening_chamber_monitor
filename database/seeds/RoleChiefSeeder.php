<?php

use Illuminate\Database\Seeder;

class RoleChiefSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $role = App\Role::firstOrCreate([
      'name' => 'chief',
      'display_name' => 'RESPONSABLE'
    ]);

    $permisions = App\Permission::where('name', 'read-sensor')->orWhere('name', 'read-limit')->orWhere('name', 'update-limit')->orWhere('name', 'read-user')->orWhere('name', 'create-user')->orWhere('name', 'update-user')->orWhere('name', 'delete-user')->orWhere('name', 'read-user-action')->orWhere('name', 'delete-user-action')->orWhere('name', 'read-room')->orWhere('name', 'update-room')->get()->toArray();

    $role->attachPermissions($permisions);
  }
}
