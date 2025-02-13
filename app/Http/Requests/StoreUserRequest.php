<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
        ];
    }

    /*Mensajes de error */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'Ingresa texto valido',
            'name.max' => 'Maximo 255 caracteres',

            'lastname.required' => 'El apellido es obligatorio.',
            'lastname.string' => 'Ingresa texto valido',
            'lastname.max' => 'Mazimo 255 caracteres',

            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.string' => 'Ingresa texto valido',
            'username.max' => 'Maximo 255 caracteres',
            'username.unique' => 'El nombre de usuario ya está en uso.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa coprreo valido',
            'email.unique' => 'El correo electrónico ya está registrado en el sistema.',
        ];
    }
}
