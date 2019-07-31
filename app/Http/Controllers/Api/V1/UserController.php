<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use App\Http\Requests\UserForm;
use Illuminate\Http\Request;
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
    if ($request->has('enabled')) {
      $list = $list->whereEnabled(json_decode($request->input('enabled'), true));
    }
    if ($request->has('sortBy')) {
      if ($request->sortBy != 'null') {
        $list = $list->orderBy($request->sortBy, $request->input('direction') ?? 'asc');
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
    $item->fill($request->all());
    $item->save();
    return $item;
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
