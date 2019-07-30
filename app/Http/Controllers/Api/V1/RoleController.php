<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Role;

/** @resource Role
 *
 * Resource to retrieve and show Role data
 */

class RoleController extends Controller
{
	/**
	 * Display a listing of the roles data.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return Role::where('name', '!=', 'admin')->get();
	}

	/**
	 * Display the specified role.
	 *
	 * @param  \App\Role  $role
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		return Role::findOrFail($id);
	}

	/**
	 * Display a listing of the permissions related to a role.
	 *
	 * @param  \App\Role  $role_id
	 * @return \Illuminate\Http\Response
	 */
	public function get_permissions($role_id) {
		$role = Role::findOrFail($role_id);
		return $role->permissions;
	}

	/**
	 * Display a specified permission related to a role.
	 *
	 * @param  \App\Role  $role_id
	 * @param  \App\Permission $permission_id
	 * @return \Illuminate\Http\Response
	 */
	public function get_permission($role_id, $permission_id) {
		$role = Role::findOrFail($role_id);
		$permission = Permission::findOrFail($permission_id);
		if ($role->permissions->contains($permission)) {
			return $permission;
		} else {
			return App::abort(404);
		}
	}

	/**
	 * Attach permission to a role.
	 *
	 * @param  \App\Role  $role_id
	 * @param  \App\Permission $permission_id
	 * @return \Illuminate\Http\Response
	 */
	public function set_permission($role_id, $permission_id) {
		$role = Role::findOrFail($role_id);
		$permission = Permission::findOrFail($permission_id);
		if (!$role->permissions->contains($permission)) {
			$role->attachPermission($permission);
			$role = Role::findOrFail($role_id);
			$role->permissions;
			return $role;
		} else {
			abort(403);
		}
	}

	/**
	 * Detach permission from a role.
	 *
	 * @param  \App\Role  $role_id
	 * @param  \App\Permission $permission_id
	 * @return \Illuminate\Http\Response
	 */
	public function unset_permission($role_id, $permission_id) {
		$role = Role::findOrFail($role_id);
		$permission = Permission::findOrFail($permission_id);
		$role->detachPermission($permission);
		$role = Role::findOrFail($role_id);
		$role->permissions;
		return $role;
	}
}
