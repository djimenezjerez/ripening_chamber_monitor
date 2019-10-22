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
		$user = App\User::firstOrCreate(
			['username' => 'admin'],
			['password' => bcrypt('admin')]
		);

		$role = App\Role::firstOrCreate(
			['name' => 'admin'],
			[
				'display_name' => 'Administrador',
				'description' => 'Administrador'
			]
		);

		$user->attachRole($role);

		$role = App\Role::firstOrCreate(
			['name' => 'chief'],
			[
				'display_name' => 'Responsable',
				'description' => 'Gestión del sistema'
			]
		);

		$role = App\Role::firstOrCreate([
			['name' => 'monitor'],
			[
				'display_name' => 'Monitor',
				'description' => 'Monitoreo de parámetros'
			]
		);
	}
}