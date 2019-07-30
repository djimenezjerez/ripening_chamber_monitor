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
    $users = User::where('username', '!=', 'admin');
    if ($request->has('search')) {
      if ($request->search != 'null' && $request->search != '') {
        $search = $request->search;
        $users = $users->where(function ($query) use ($search) {
          foreach (Schema::getColumnListing(User::getTableName()) as $column) {
            $query = $query->orWhere($column, 'ilike', '%' . $search . '%');
          }
        });
      }
    }
    if ($request->has('enabled')) {
      $users = $users->whereEnabled(json_decode($request->input('enabled'), true));
    }
    if ($request->has('sortBy')) {
      if ($request->sortBy != 'null') {
        $users = $users->orderBy($request->sortBy, $request->input('direction') ?? 'asc');
      }
    }
    return $users->paginate($request->input('per_page') ?? 10);
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
    $user = User::findOrFail($id);
    $user->fill($request->all());
    $user->save();
    return $user;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\User  $user
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //TODO when user-actions table have been created
  }
}
