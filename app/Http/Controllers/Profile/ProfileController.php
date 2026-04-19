<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Services\AuditService;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        return view('dashboard.profile', ['user' => $request->user()]);
    }

    public function security(Request $request): View
    {
        return view('dashboard.security', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'min:3', 'max:100', 'regex:/^[\pL\s\-]+$/u'],
            'phone' => ['nullable', 'string', 'regex:/^(\+62|62|0)[0-9]{9,12}$/'],
        ]);

        $user    = $request->user();
        $oldData = $user->only(['name', 'phone']);

        $user->update([
            'name'  => strip_tags($validated['name']),
            'phone' => $validated['phone'] ?? null,
        ]);

        AuditService::logProfileUpdate($user, $oldData, $user->fresh()->only(['name', 'phone']));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validateWithBag('passwordUpdate', [
            'current_password' => ['required', 'string', 'current_password'],
            'password'         => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(),
            ],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        AuditService::logPasswordChange($request->user());

        return back()->with('success', 'Password berhasil diubah.');
    }
}