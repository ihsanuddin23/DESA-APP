<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"))
            ->when($request->role,   fn($q, $r) => $q->where('role', $r))
            ->when($request->status === 'active',   fn($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn($q) => $q->where('is_active', false))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load(['auditLogs' => fn($q) => $q->orderByDesc('created_at')->limit(20)]);
        return view('admin.users.show', compact('user'));
    }

    public function toggleActive(Request $request, User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak bisa menonaktifkan akun sendiri.']);
        }

        $user->update(['is_active' => !$user->is_active]);
        AuditService::log(
            'admin_toggle_active',
            "User {$user->email} set to " . ($user->is_active ? 'active' : 'inactive'),
            $user
        );

        return back()->with('success', "Akun {$user->name} berhasil " . ($user->is_active ? 'diaktifkan' : 'dinonaktifkan') . '.');
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:admin,staff_desa,rw,rt,warga'],
            'rt'   => ['nullable', 'string', 'max:3', 'required_if:role,rt'],
            'rw'   => ['nullable', 'string', 'max:3', 'required_if:role,rw,required_if:role,rt'],
        ], [
            'rt.required_if' => 'Ketua RT wajib punya nomor RT.',
            'rw.required_if' => 'Ketua RW wajib punya nomor RW. Ketua RT juga wajib isi RW.',
        ]);

        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak bisa mengubah role akun sendiri.']);
        }

        $oldRole = $user->role;

        // Reset rt/rw kalau role bukan rt atau rw
        $updateData = ['role' => $validated['role']];
        if (in_array($validated['role'], ['rt', 'rw'])) {
            $updateData['rt'] = $validated['role'] === 'rt' ? ($validated['rt'] ?? null) : null;
            $updateData['rw'] = $validated['rw'] ?? null;
        } else {
            $updateData['rt'] = null;
            $updateData['rw'] = null;
        }

        $user->update($updateData);

        AuditService::log(
            'admin_change_role',
            "Role {$user->email}: {$oldRole} → {$validated['role']}",
            $user
        );

        return back()->with('success', "Role {$user->name} berhasil diubah menjadi " . ($user->role_label) . '.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak bisa menghapus akun sendiri.']);
        }

        $name = $user->name;
        AuditService::log('admin_delete_user', "Deleted user: {$user->email}", $user);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', "Akun {$name} berhasil dihapus.");
    }
}