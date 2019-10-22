<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $role = App\Role::firstOrCreate(
      ['name' => 'admin'],
      [
        'display_name' => 'Administración',
        'description' => 'Rol administrador'
      ]
    );

    $user = App\User::firstOrCreate(
      ['name' => 'Administrador'],
      [
        'username' => 'admin',
        'password' => bcrypt('admin')
      ]
    );

    $user->syncRoles([$role]);
  }
}
