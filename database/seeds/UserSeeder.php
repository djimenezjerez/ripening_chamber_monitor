<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$user = App\User::create([
			'username' => 'admin',
			'password' => bcrypt('admin'),
		]);

		$role = App\Role::create([
			'name' => 'admin',
			'display_name' => 'Administrador',
			'description' => 'Administrador',
		]);

		$user->attachRole($role);

		$role = App\Role::create([
			'name' => 'chief',
			'display_name' => 'Responsable',
			'description' => 'Gestión del sistema',
		]);

		$role = App\Role::create([
			'name' => 'monitor',
			'display_name' => 'Monitor',
			'description' => 'Monitoreo de parámetros',
		]);
	}
}
