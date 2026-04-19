<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class GaleriController extends Controller
{
    public function index(Request $request): View
    {
        $galeri = Galeri::query()
            ->when($request->search, fn($q, $s) => $q->where('judul', 'like', "%{$s}%"))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.galeri.index', compact('galeri'));
    }

    public function create(): View
    {
        return view('admin.galeri.create');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'judul'      => 'required|string|max:255',
                'keterangan' => 'nullable|string|max:255',
                'file'       => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
                'status'     => 'required|in:published,draft',
            ]);

            $validated['file']    = $request->file('file')->store('galeri', 'public');
            $validated['user_id'] = auth()->id();

            $item = Galeri::create($validated);
            Log::info('CREATE GALERI berhasil', ['id' => $item->id]);

            return redirect()->route('admin.galeri.index')
                ->with('success', "✅ Foto \"{$item->judul}\" berhasil ditambahkan.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('CREATE GALERI error', ['msg' => $e->getMessage()]);
            return redirect()->back()->withInput()
                ->with('error', '❌ Gagal simpan: ' . $e->getMessage());
        }
    }

    public function edit(Galeri $galeri): View
    {
        return view('admin.galeri.edit', compact('galeri'));
    }

    public function update(Request $request, Galeri $galeri): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'judul'      => 'required|string|max:255',
                'keterangan' => 'nullable|string|max:255',
                'file'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
                'status'     => 'required|in:published,draft',
            ]);

            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                if ($galeri->file && Storage::disk('public')->exists($galeri->file)) {
                    Storage::disk('public')->delete($galeri->file);
                }
                $validated['file'] = $request->file('file')->store('galeri', 'public');
            } else {
                unset($validated['file']);
            }

            $galeri->update($validated);

            return redirect()->route('admin.galeri.index')
                ->with('success', "✅ Foto \"{$galeri->judul}\" berhasil diperbarui.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('UPDATE GALERI error', ['msg' => $e->getMessage()]);
            return redirect()->back()->withInput()
                ->with('error', '❌ Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy(Galeri $galeri): RedirectResponse
    {
        try {
            $judul = $galeri->judul;

            if ($galeri->file && Storage::disk('public')->exists($galeri->file)) {
                Storage::disk('public')->delete($galeri->file);
            }

            $galeri->delete();

            return redirect()->route('admin.galeri.index')
                ->with('success', "✅ Foto \"{$judul}\" berhasil dihapus.");

        } catch (\Throwable $e) {
            return redirect()->back()
                ->with('error', '❌ Gagal hapus: ' . $e->getMessage());
        }
    }
}
