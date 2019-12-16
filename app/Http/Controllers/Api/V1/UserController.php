<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use App\Http\Requests\UserForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $list = User::where('username', '!=', 'admin');
        if ($request->has('enabled')) {
            $list = $list->whereEnabled(json_decode($request->input('enabled'), true));
        }
        if ($request->has('search')) {
            if ($request->search != 'null' && $request->search != '') {
                $search = $request->search;
                $list = $list->where(function ($query) use ($search) {
                    foreach (Schema::getColumnListing(User::getTableName()) as $column) {
                        $query = $query->orWhere($column, 'ilike', '%' . $search . '%');
                    }
                });
            }
        }
        if ($request->has('sortBy') && $request->has('sortDesc')) {
            if (count($request->sortDesc) > 0 && count($request->sortBy) == count($request->sortDesc)) {
                foreach($request->sortDesc as $i => $sortDesc) {
                    $list = $list->orderBy($request->sortBy[$i], filter_var($sortDesc, FILTER_VALIDATE_BOOLEAN) ? 'desc' : 'asc');
                }
            }
        }
        return $list->paginate($request->input('per_page') ?? 10);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(UserForm $request)
    {
        return User::create($request->all());
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\User  $user
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        return User::findOrFail($id);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\User  $user
    * @return \Illuminate\Http\Response
    */
    public function update(UserForm $request, $id)
    {
        $item = User::findOrFail($id);
        $logged_user = Auth::user();
        if ($logged_user->id == $item->id) {
            if ($request->has('old_password') && $request->has('password')) {
                if (!Hash::check($request->old_password, $item->password)) {
                    return response()->json([
                        'message' => 'No autorizado',
                        'errors' => [
                            'type' => ['Verifique la antigua contraseña'],
                        ],
                    ], 422);
                }
            }
        } elseif (!$logged_user->hasPermission('update-user')) {
            unset($request['password']);
        }
        $item->fill($request->all());
        $item->save();
        return $item;
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return $user;
    }

    public function get_roles($id)
    {
        $user = User::findOrFail($id);
        return $user->roles;
    }

    public function set_roles(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->syncRoles($request->input('roles'));
        return $user->roles;
    }
}
