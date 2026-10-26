<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, $this->user()->password)) {
                        $fail('Password lama tidak sesuai dengan password saat ini.');
                    }
                },
            ],
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'different:current_password',
            ],
            'password_confirmation' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password baru minimal harus 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password baru tidak cocok.',
            'password.different'        => 'Password baru tidak boleh sama dengan password lama.',
            'password_confirmation.required' => 'Konfirmasi password baru wajib diisi.',
        ];
    }
}