#!/usr/bin/env sh

# Migration
php artisan migrate

# Seeders
php artisan db:seed --class=ModuleSeeder
php artisan db:seed --class=LimitPermissionSeeder
php artisan db:seed --class=SensorPermissionSeeder
php artisan db:seed --class=UserActionPermissionSeeder
php artisan db:seed --class=UserPermissionSeeder
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=RoleChiefSeeder
php artisan db:seed --class=RoleMonitorSeeder
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=AdminPermissionSeeder