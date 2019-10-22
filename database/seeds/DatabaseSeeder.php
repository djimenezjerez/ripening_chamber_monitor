<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    $this->command->info('Unguarding models');
    Model::unguard();

    $tables = [
      'magnitude_room',
      'permission_role',
      'permission_user',
      'role_user',
      'user_actions',
      'devices',
      'magnitudes',
      'measurements',
      'modules',
      'users',
      'roles',
      'rooms',
      'permissions'
    ];

    $this->command->info('Truncating existing tables');
    foreach ($tables as $table) {
      DB::statement('TRUNCATE TABLE ' . $table . ' CASCADE;');
    }

    $this->call(AdminSeeder::class);
    Auth::login(App\User::first());
    $this->call(ModuleSeeder::class);
    $this->call(MagnitudeSeeder::class);
    $this->call(DevicePermissionSeeder::class);
    $this->call(MagnitudePermissionSeeder::class);
    $this->call(SensorPermissionSeeder::class);
    $this->call(UserActionPermissionSeeder::class);
    $this->call(UserPermissionSeeder::class);
    $this->call(RolePermissionSeeder::class);
    $this->call(RoomPermissionSeeder::class);
    $this->call(RoleChiefSeeder::class);
    $this->call(RoleMonitorSeeder::class);
    $this->call(AdminPermissionSeeder::class);

    // Fake data
    $this->call(DeviceSeeder::class);
    $this->call(RoomSeeder::class);
    $this->call(MeasurementSeeder::class);
    $this->call(MagnitudeRoomSeeder::class);
  }
}
