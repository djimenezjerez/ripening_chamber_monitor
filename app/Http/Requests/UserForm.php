<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserForm extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    if (in_array($this->method(), ['PUT', 'PATCH'])) {
      $user = User::find($this->route()->parameters()['user']);
      $authenticated = Auth::user();

      if ($user) {
        if ($authenticated->can('update-user') || $user->id==$authenticated->id) {
          return true;
        }
      }
      return false;
    }

    return true;
  }

  /**
   * Validate request
   * @return
   */
  public function validate()
  {
    switch ($this->method()) {
      case 'POST': {
          if (User::whereUsername($this->input('username'))->count() == 0) return parent::validate();
        }
      case 'PUT':
      case 'PATCH': {
          if (User::find($this->input('id'))) return parent::validate();
        }
      default: {
          return abort(404);
        }
    }
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $this->sanitize();

    $rules = [
      'password' => 'string|min:4',
      'username' => 'string|min:4|unique:users,username',
      'name' => 'string|min:4',
      'phone' => 'nullable|integer|min:7|max:8',
      'charge' => 'nullable|string'
    ];

    switch ($this->method()) {
      case 'POST': {
          foreach (array_slice($rules, 0, 3) as $key => $rule) {
            $rules[$key] = implode('|', ['required', $rule]);
          }
          return $rules;
        }
      case 'PUT':
      case 'PATCH': {
          return $rules;
        }
    }
  }

  public function messages()
  {
    return [
      'required' => 'El campo es requerido',
      'string' => 'El campo solo puede contener letras y números',
      'unique' => 'El registro ya existe',
      'name.min' => 'El nombre debe contener al menos 4 caracteres',
      'username.min' => 'El nombre de usuario debe contener al menos 3 caracteres',
      'password.min' => 'La contraseña debe contener al menos 3 caracteres',
      'phone.min' => 'El teléfono debe contener al menos 7 caracteres',
      'phone.max' => 'El teléfono puede contener máximo 8 caracteres'
    ];
  }

  public function sanitize()
  {
    $input = $this->all();

    if (array_key_exists('name', $input)) $input['name'] = mb_strtoupper($input['name']);
    if (array_key_exists('username', $input)) $input['username'] = mb_strtolower($input['username']);
    if (array_key_exists('charge', $input)) $input['charge'] = mb_strtoupper($input['charge']);
    if (array_key_exists('newPassword', $input)) $input['password'] = Hash::make($input['newPassword']);

    $this->replace($input);
  }
}
