<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'name' => 'string|min:4',
            'display_name' => 'string|min:4',
            'mac' => 'string|min:17|max:17',
            'ip' => 'ipv4'
        ];
        switch ($this->method()) {
            case 'POST': {
                foreach (array_slice($rules, 0, 4) as $key => $rule) {
                    $rules[$key] = implode('|', ['required', $rule]);
                }
                $rules['name'] .= implode('|', ['unique:devices,name', $rules['name']]);
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
            'name.min' => 'El código debe contener al menos 4 caracteres',
            'mac.min' => 'La dirección MAC debe contener 17 caracteres',
            'mac.max' => 'La dirección MAC debe contener 17 caracteres',
            'ip' => 'No es una dirección IP válida'
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        if (array_key_exists('name', $input)) $input['name'] = mb_strtoupper($input['name']);
        if (array_key_exists('mac', $input)) {
            $input['mac'] = str_replace('-', ':', $input['mac']);
            $input['mac'] = str_replace('.', ':', $input['mac']);
        }
        $this->replace($input);
    }
}
