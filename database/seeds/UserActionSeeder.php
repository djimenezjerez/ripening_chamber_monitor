<?php

use Illuminate\Database\Seeder;

class UserActionSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$total_items = 20;

		factory(App\UserAction::class, $total_items)->create();
	}
}
