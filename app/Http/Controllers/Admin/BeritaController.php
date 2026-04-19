<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(Request $request): View
    {
        $berita = Berita::query()
            ->when($request->search, fn($q, $s) => $q->where('judul', 'like', "%{$s}%"))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->kategori, fn($q, $k) => $q->where('kategori', $k))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.berita.index', compact('berita'));
    }

    public function create(): View
    {
        return view('admin.berita.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // ═══════════════════════════════════════════════════════════════════
        // DEBUG MODE ON — akan melempar error detail kalau ada masalah
        // ═══════════════════════════════════════════════════════════════════

        Log::info('STORE BERITA: request masuk', [
            'method'        => $request->method(),
            'has_file'      => $request->hasFile('foto'),
            'all_files'     => array_keys($request->allFiles()),
            'content_type'  => $request->header('Content-Type'),
            'all_input'     => $request->except(['konten', 'foto']),
            'user_id'       => auth()->id(),
        ]);

        try {
            // ── Validasi ──────────────────────────────────────────────────
            $validated = $request->validate([
                'judul'        => 'required|string|max:255',
                'kategori'     => 'required|string|max:100',
                'ringkasan'    => 'nullable|string|max:500',
                'konten'       => 'required|string',
                'foto'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'penulis'      => 'nullable|string|max:100',
                'status'       => 'required|in:draft,published',
                'published_at' => 'nullable|date',
            ]);

            Log::info('STORE BERITA: validasi lolos', ['validated_keys' => array_keys($validated)]);

            // ── Handle upload foto ────────────────────────────────────────
            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                $validated['foto'] = $request->file('foto')->store('berita', 'public');
                Log::info('STORE BERITA: foto tersimpan', ['path' => $validated['foto']]);
            }

            // ── Set slug dan user_id ──────────────────────────────────────
            $validated['slug']    = Str::slug($validated['judul']) . '-' . Str::random(5);
            $validated['user_id'] = auth()->id();

            // ── Set published_at kalau status published dan tanggal kosong ─
            if ($validated['status'] === 'published' && empty($validated['published_at'])) {
                $validated['published_at'] = now();
            }

            Log::info('STORE BERITA: akan insert ke database', [
                'data_untuk_insert' => collect($validated)->except('konten')->all(),
            ]);

            // ── Insert ke database ────────────────────────────────────────
            $item = Berita::create($validated);

            Log::info('STORE BERITA: BERHASIL', [
                'id'    => $item->id,
                'judul' => $item->judul,
                'slug'  => $item->slug,
            ]);

            return redirect()->route('admin.berita.index')
                ->with('success', "✅ Berita \"{$item->judul}\" berhasil ditambahkan (ID: {$item->id}).");

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Kalau validasi gagal, biarkan Laravel yang handle (auto redirect back)
            Log::warning('STORE BERITA: validasi gagal', ['errors' => $e->errors()]);
            throw $e;

        } catch (\Throwable $e) {
            // Tangkap error apa saja, log detail, dan tampilkan ke user
            Log::error('STORE BERITA: ERROR SAAT INSERT', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Gagal simpan berita. Error: ' . $e->getMessage());
        }
    }

    public function show(Berita $berita): View
    {
        return view('admin.berita.show', compact('berita'));
    }

    public function edit(Berita $berita): View
    {
        return view('admin.berita.edit', compact('berita'));
    }

    public function update(Request $request, Berita $berita): RedirectResponse
    {
        Log::info('UPDATE BERITA: request masuk', [
            'id'           => $berita->id,
            'method'       => $request->method(),
            'has_file'     => $request->hasFile('foto'),
            'all_files'    => array_keys($request->allFiles()),
            'content_type' => $request->header('Content-Type'),
            'all_input'    => $request->except(['konten', 'foto']),
        ]);

        try {
            $validated = $request->validate([
                'judul'        => 'required|string|max:255',
                'kategori'     => 'required|string|max:100',
                'ringkasan'    => 'nullable|string|max:500',
                'konten'       => 'required|string',
                'foto'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'penulis'      => 'nullable|string|max:100',
                'status'       => 'required|in:draft,published',
                'published_at' => 'nullable|date',
            ]);

            // ── Handle upload foto baru ────────────────────────────────────
            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                if ($berita->foto && Storage::disk('public')->exists($berita->foto)) {
                    Storage::disk('public')->delete($berita->foto);
                    Log::info('UPDATE BERITA: foto lama dihapus', ['path' => $berita->foto]);
                }
                $validated['foto'] = $request->file('foto')->store('berita', 'public');
                Log::info('UPDATE BERITA: foto baru tersimpan', ['path' => $validated['foto']]);
            } else {
                unset($validated['foto']);
            }

            if ($validated['status'] === 'published' && empty($berita->published_at) && empty($validated['published_at'])) {
                $validated['published_at'] = now();
            }

            $berita->update($validated);

            Log::info('UPDATE BERITA: BERHASIL', ['id' => $berita->id]);

            return redirect()->route('admin.berita.index')
                ->with('success', "✅ Berita \"{$berita->judul}\" berhasil diperbarui.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('UPDATE BERITA: validasi gagal', ['errors' => $e->errors()]);
            throw $e;

        } catch (\Throwable $e) {
            Log::error('UPDATE BERITA: ERROR SAAT UPDATE', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Gagal update berita. Error: ' . $e->getMessage());
        }
    }

    public function destroy(Berita $berita): RedirectResponse
    {
        try {
            $judul = $berita->judul;

            if ($berita->foto && Storage::disk('public')->exists($berita->foto)) {
                Storage::disk('public')->delete($berita->foto);
            }

            $berita->delete();

            Log::info('DELETE BERITA: berhasil', ['id' => $berita->id, 'judul' => $judul]);

            return redirect()->route('admin.berita.index')
                ->with('success', "✅ Berita \"{$judul}\" berhasil dihapus.");

        } catch (\Throwable $e) {
            Log::error('DELETE BERITA: ERROR', [
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', '❌ Gagal hapus berita. Error: ' . $e->getMessage());
        }
    }
}
