<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'min:3', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'email'    => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'nik'      => ['nullable', 'digits:16', 'unique:users,nik'],
            'phone'    => ['nullable', 'string', 'regex:/^(\+62|62|0)[0-9]{9,12}$/', 'max:15'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()       // huruf besar + kecil
                    ->numbers()         // harus ada angka
                    ->symbols()         // harus ada simbol
                    ->uncompromised(),  // cek password breach (Have I Been Pwned)
            ],
            'captcha'  => ['required', 'string'],
            'terms'    => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Nama lengkap wajib diisi.',
            'name.min'             => 'Nama minimal 3 karakter.',
            'name.regex'           => 'Nama hanya boleh berisi huruf dan spasi.',
            'email.required'       => 'Email wajib diisi.',
            'email.email'          => 'Format email tidak valid.',
            'email.unique'         => 'Email sudah terdaftar.',
            'nik.digits'           => 'NIK harus terdiri dari 16 digit angka.',
            'nik.unique'           => 'NIK sudah terdaftar.',
            'phone.regex'          => 'Format nomor telepon tidak valid (contoh: 08123456789).',
            'password.required'    => 'Password wajib diisi.',
            'password.confirmed'   => 'Konfirmasi password tidak cocok.',
            'captcha.required'     => 'Kode CAPTCHA wajib diisi.',
            'terms.required'       => 'Anda harus menyetujui syarat & ketentuan.',
            'terms.accepted'       => 'Anda harus menyetujui syarat & ketentuan.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $expected = session('captcha_answer');
            $input    = trim($this->input('captcha'));

            if (!$expected || $input !== (string) $expected) {
                $v->errors()->add('captcha', 'Kode CAPTCHA tidak sesuai.');
                session()->forget('captcha_answer');
            }
        });
    }
}
