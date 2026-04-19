<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email:rfc,dns', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'captcha'  => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 8 karakter.',
            'captcha.required'  => 'Kode CAPTCHA wajib diisi.',
        ];
    }

    /**
     * Validate CAPTCHA answer from session.
     * Called after standard validation passes.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $expected = session('captcha_answer');
            $input    = trim($this->input('captcha'));

            if (!$expected || $input !== (string) $expected) {
                $v->errors()->add('captcha', 'Kode CAPTCHA tidak sesuai.');
                // Regenerate CAPTCHA immediately
                session()->forget('captcha_answer');
            }
        });
    }
}
