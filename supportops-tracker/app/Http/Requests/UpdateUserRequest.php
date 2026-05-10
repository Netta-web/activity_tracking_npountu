<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', "unique:users,email,{$userId}"],
            'password'   => ['nullable', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()],
            'role'       => ['required', 'in:admin,support'],
            'department' => ['nullable', 'string', 'max:100'],
            'is_active'  => ['boolean'],
        ];
    }
}
