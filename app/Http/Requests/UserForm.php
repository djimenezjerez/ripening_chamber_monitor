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
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $this->sanitize();

    $rules = [
      'newPassword' => 'string|min:4',
      'username' => 'string|min:4',
      'name' => 'string|min:4',
      'phone' => 'nullable|digits_between:7,8',
      'charge' => 'nullable|string'
    ];

    switch ($this->method()) {
      case 'POST': {
          foreach (array_slice($rules, 0, 3) as $key => $rule) {
            $rules[$key] = implode('|', ['required', $rule]);
          }
          $rules['username'] .= implode('|', ['unique:users,username', $rules['username']]);
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
      'integer' => 'El campo solo puede contener números',
      'unique' => 'El registro ya existe',
      'name.min' => 'El nombre debe contener al menos 4 caracteres',
      'username.min' => 'El nombre de usuario debe contener al menos 4 caracteres',
      'newPassword.min' => 'La contraseña debe contener al menos 4 caracteres',
      'digits_between' => 'El teléfono debe contener de 7 a 8 caracteres',
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
