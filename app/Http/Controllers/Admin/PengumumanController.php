<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    public function index(Request $request): View
    {
        $pengumuman = Pengumuman::query()
            ->when($request->search, fn($q, $s) => $q->where('judul', 'like', "%{$s}%"))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->prioritas, fn($q, $p) => $q->where('prioritas', $p))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    public function create(): View
    {
        return view('admin.pengumuman.create');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'judul'          => 'required|string|max:255',
                'isi'            => 'nullable|string',
                'prioritas'      => 'required|in:penting,info,umum',
                'status'         => 'required|in:aktif,nonaktif',
                'berlaku_hingga' => 'nullable|date|after_or_equal:today',
                'file_lampiran'  => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            ]);

            if ($request->hasFile('file_lampiran') && $request->file('file_lampiran')->isValid()) {
                $validated['file_lampiran'] = $request->file('file_lampiran')->store('pengumuman', 'public');
            }

            $validated['user_id'] = auth()->id();

            $item = Pengumuman::create($validated);
            Log::info('CREATE PENGUMUMAN berhasil', ['id' => $item->id, 'judul' => $item->judul]);

            return redirect()->route('admin.pengumuman.index')
                ->with('success', "✅ Pengumuman \"{$item->judul}\" berhasil ditambahkan.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('CREATE PENGUMUMAN error', ['msg' => $e->getMessage()]);
            return redirect()->back()->withInput()
                ->with('error', '❌ Gagal simpan: ' . $e->getMessage());
        }
    }

    public function edit(Pengumuman $pengumuman): View
    {
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'judul'          => 'required|string|max:255',
                'isi'            => 'nullable|string',
                'prioritas'      => 'required|in:penting,info,umum',
                'status'         => 'required|in:aktif,nonaktif',
                'berlaku_hingga' => 'nullable|date',
                'file_lampiran'  => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            ]);

            if ($request->hasFile('file_lampiran') && $request->file('file_lampiran')->isValid()) {
                if ($pengumuman->file_lampiran && Storage::disk('public')->exists($pengumuman->file_lampiran)) {
                    Storage::disk('public')->delete($pengumuman->file_lampiran);
                }
                $validated['file_lampiran'] = $request->file('file_lampiran')->store('pengumuman', 'public');
            } else {
                unset($validated['file_lampiran']);
            }

            $pengumuman->update($validated);
            Log::info('UPDATE PENGUMUMAN berhasil', ['id' => $pengumuman->id]);

            return redirect()->route('admin.pengumuman.index')
                ->with('success', "✅ Pengumuman \"{$pengumuman->judul}\" berhasil diperbarui.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('UPDATE PENGUMUMAN error', ['msg' => $e->getMessage()]);
            return redirect()->back()->withInput()
                ->with('error', '❌ Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy(Pengumuman $pengumuman): RedirectResponse
    {
        try {
            $judul = $pengumuman->judul;

            if ($pengumuman->file_lampiran && Storage::disk('public')->exists($pengumuman->file_lampiran)) {
                Storage::disk('public')->delete($pengumuman->file_lampiran);
            }

            $pengumuman->delete();

            return redirect()->route('admin.pengumuman.index')
                ->with('success', "✅ Pengumuman \"{$judul}\" berhasil dihapus.");

        } catch (\Throwable $e) {
            return redirect()->back()
                ->with('error', '❌ Gagal hapus: ' . $e->getMessage());
        }
    }
}