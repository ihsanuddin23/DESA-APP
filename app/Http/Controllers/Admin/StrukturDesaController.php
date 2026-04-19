<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StrukturDesa;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class StrukturDesaController extends Controller
{
    public function index(Request $request): View
    {
        $strukturDesa = StrukturDesa::query()
            ->when($request->search, fn($q, $s) => $q->where('nama', 'like', "%{$s}%")->orWhere('jabatan', 'like', "%{$s}%"))
            ->orderBy('urutan')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.struktur-desa.index', compact('strukturDesa'));
    }

    public function create(): View
    {
        return view('admin.struktur-desa.create');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'nama'          => 'required|string|max:255',
                'jabatan'       => 'required|string|max:255',
                'keterangan'    => 'nullable|string|max:255',
                'telepon'       => 'nullable|string|max:20',
                'urutan'        => 'nullable|integer|min:0|max:999',
                'tampil_publik' => 'nullable|boolean',
                'foto'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            $validated['tampil_publik'] = $request->has('tampil_publik');
            $validated['urutan']        = $validated['urutan'] ?? 0;

            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                $validated['foto'] = $request->file('foto')->store('struktur', 'public');
            }

            $item = StrukturDesa::create($validated);
            Log::info('CREATE STRUKTUR berhasil', ['id' => $item->id, 'nama' => $item->nama]);

            return redirect()->route('admin.struktur-desa.index')
                ->with('success', "✅ {$item->jabatan} \"{$item->nama}\" berhasil ditambahkan.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('CREATE STRUKTUR error', ['msg' => $e->getMessage()]);
            return redirect()->back()->withInput()
                ->with('error', '❌ Gagal simpan: ' . $e->getMessage());
        }
    }

    public function edit(StrukturDesa $strukturDesa): View
    {
        return view('admin.struktur-desa.edit', compact('strukturDesa'));
    }

    public function update(Request $request, StrukturDesa $strukturDesa): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'nama'          => 'required|string|max:255',
                'jabatan'       => 'required|string|max:255',
                'keterangan'    => 'nullable|string|max:255',
                'telepon'       => 'nullable|string|max:20',
                'urutan'        => 'nullable|integer|min:0|max:999',
                'tampil_publik' => 'nullable|boolean',
                'foto'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            $validated['tampil_publik'] = $request->has('tampil_publik');
            $validated['urutan']        = $validated['urutan'] ?? 0;

            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                if ($strukturDesa->foto && Storage::disk('public')->exists($strukturDesa->foto)) {
                    Storage::disk('public')->delete($strukturDesa->foto);
                }
                $validated['foto'] = $request->file('foto')->store('struktur', 'public');
            } else {
                unset($validated['foto']);
            }

            $strukturDesa->update($validated);

            return redirect()->route('admin.struktur-desa.index')
                ->with('success', "✅ Data \"{$strukturDesa->nama}\" berhasil diperbarui.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('UPDATE STRUKTUR error', ['msg' => $e->getMessage()]);
            return redirect()->back()->withInput()
                ->with('error', '❌ Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy(StrukturDesa $strukturDesa): RedirectResponse
    {
        try {
            $nama = $strukturDesa->nama;

            if ($strukturDesa->foto && Storage::disk('public')->exists($strukturDesa->foto)) {
                Storage::disk('public')->delete($strukturDesa->foto);
            }

            $strukturDesa->delete();

            return redirect()->route('admin.struktur-desa.index')
                ->with('success', "✅ Data \"{$nama}\" berhasil dihapus.");

        } catch (\Throwable $e) {
            return redirect()->back()
                ->with('error', '❌ Gagal hapus: ' . $e->getMessage());
        }
    }
}
