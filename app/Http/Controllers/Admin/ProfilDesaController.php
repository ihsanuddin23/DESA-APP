<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilDesa;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfilDesaController extends Controller
{
    /**
     * Form edit profil desa.
     */
    public function edit(): View
    {
        $profil = ProfilDesa::get();
        return view('admin.profil-desa.edit', compact('profil'));
    }

    /**
     * Update profil desa.
     */
    public function update(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'nama_desa'       => 'required|string|max:100',
                'kode_desa'       => 'nullable|string|max:20',
                'kepala_desa'     => 'nullable|string|max:100',
                'tahun_berdiri'   => 'nullable|string|max:4',
                'visi'            => 'nullable|string|max:5000',
                'misi'            => 'nullable|string|max:5000',
                'sejarah'         => 'nullable|string|max:10000',
                'geografis'       => 'nullable|string|max:5000',
                'demografi'       => 'nullable|string|max:5000',
                'luas_wilayah_km2'=> 'nullable|integer|min:0',
                'jumlah_dusun'    => 'nullable|integer|min:0',
                'jumlah_rw'       => 'nullable|integer|min:0',
                'jumlah_rt'       => 'nullable|integer|min:0',
                'alamat_kantor'   => 'nullable|string|max:255',
                'telepon'         => 'nullable|string|max:30',
                'email'           => 'nullable|email|max:100',
                'jam_pelayanan'   => 'nullable|string|max:100',
                'logo'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'foto_kantor'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            ]);

            $profil = ProfilDesa::get();

            // Handle upload logo
            if ($request->hasFile('logo')) {
                if ($profil->logo && Storage::disk('public')->exists($profil->logo)) {
                    Storage::disk('public')->delete($profil->logo);
                }
                $validated['logo'] = $request->file('logo')->store('profil-desa', 'public');
            }

            // Handle upload foto kantor
            if ($request->hasFile('foto_kantor')) {
                if ($profil->foto_kantor && Storage::disk('public')->exists($profil->foto_kantor)) {
                    Storage::disk('public')->delete($profil->foto_kantor);
                }
                $validated['foto_kantor'] = $request->file('foto_kantor')->store('profil-desa', 'public');
            }

            $profil->update($validated);

            Log::info('UPDATE PROFIL DESA', ['by' => auth()->id()]);

            return redirect()->route('admin.profil-desa.edit')
                ->with('success', '✅ Profil desa berhasil diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('UPDATE PROFIL DESA error', ['msg' => $e->getMessage()]);
            return back()->withInput()
                ->with('error', '❌ Gagal menyimpan: ' . $e->getMessage());
        }
    }
}
