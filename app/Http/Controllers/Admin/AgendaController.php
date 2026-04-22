<?php
// app/Http/Controllers/Admin/AgendaController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AgendaController extends Controller
{
    // ══════════════════════════════════════════════════════════════════════════
    // PUBLIC VIEWS
    // ══════════════════════════════════════════════════════════════════════════

    public function public(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);
        $kategori = $request->get('kategori');

        // Agenda bulan ini untuk kalender
        $agendaBulan = Agenda::publikasi()
            ->whereMonth('tanggal_mulai', $bulan)
            ->whereYear('tanggal_mulai', $tahun)
            ->orderBy('tanggal_mulai')
            ->get();

        // Agenda mendatang
        $query = Agenda::publikasi()->mendatang()->orderBy('tanggal_mulai');

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $agendaMendatang = $query->take(10)->get();

        // Agenda highlight
        $agendaHighlight = Agenda::publikasi()
            ->highlight()
            ->mendatang()
            ->orderBy('tanggal_mulai')
            ->take(3)
            ->get();

        // Statistik
        $stats = [
            'total_bulan_ini' => Agenda::publikasi()->bulanIni()->count(),
            'mendatang' => Agenda::publikasi()->mendatang()->count(),
            'berlangsung' => Agenda::publikasi()->berlangsung()->count(),
        ];

        return view('agenda', compact(
            'agendaBulan',
            'agendaMendatang',
            'agendaHighlight',
            'stats',
            'bulan',
            'tahun',
            'kategori'
        ));
    }

    public function publicDetail(Agenda $agenda)
    {
        if ($agenda->status !== 'publikasi') {
            abort(404);
        }

        // Agenda terkait (kategori sama)
        $agendaTerkait = Agenda::publikasi()
            ->where('id', '!=', $agenda->id)
            ->where('kategori', $agenda->kategori)
            ->mendatang()
            ->orderBy('tanggal_mulai')
            ->take(3)
            ->get();

        return view('agenda-detail', compact('agenda', 'agendaTerkait'));
    }

    // ══════════════════════════════════════════════════════════════════════════
    // ADMIN CRUD
    // ══════════════════════════════════════════════════════════════════════════

    public function index(Request $request)
    {
        $query = Agenda::with('creator')->orderByDesc('tanggal_mulai');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_mulai', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_mulai', $request->tahun);
        }
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $agenda = $query->paginate(15)->withQueryString();

        // Untuk filter dropdown
        $tahunList = Agenda::selectRaw('YEAR(tanggal_mulai) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('admin.agenda.index', compact('agenda', 'tahunList'));
    }

    public function create()
    {
        return view('admin.agenda.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:5000'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'waktu_mulai' => ['nullable', 'date_format:H:i'],
            'waktu_selesai' => ['nullable', 'date_format:H:i', 'after:waktu_mulai'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'penyelenggara' => ['nullable', 'string', 'max:255'],
            'kontak_person' => ['nullable', 'string', 'max:100'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'kategori' => ['required', Rule::in(array_keys(Agenda::kategoriOptions()))],
            'status' => ['required', Rule::in(['draft', 'publikasi', 'selesai', 'dibatalkan'])],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_highlight' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['judul']) . '-' . Str::random(5);
        $validated['created_by'] = auth()->id();
        $validated['is_highlight'] = $request->boolean('is_highlight');

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('agenda', 'public');
        }

        Agenda::create($validated);

        return redirect()->route('admin.agenda.index')
            ->with('success', 'Agenda berhasil ditambahkan.');
    }

    public function show(Agenda $agenda)
    {
        return view('admin.agenda.show', compact('agenda'));
    }

    public function edit(Agenda $agenda)
    {
        return view('admin.agenda.edit', compact('agenda'));
    }

    public function update(Request $request, Agenda $agenda)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:5000'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'waktu_mulai' => ['nullable', 'date_format:H:i'],
            'waktu_selesai' => ['nullable', 'date_format:H:i'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'penyelenggara' => ['nullable', 'string', 'max:255'],
            'kontak_person' => ['nullable', 'string', 'max:100'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'kategori' => ['required', Rule::in(array_keys(Agenda::kategoriOptions()))],
            'status' => ['required', Rule::in(['draft', 'publikasi', 'selesai', 'dibatalkan'])],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_highlight' => ['nullable', 'boolean'],
        ]);

        $validated['is_highlight'] = $request->boolean('is_highlight');

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($agenda->gambar) {
                Storage::disk('public')->delete($agenda->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('agenda', 'public');
        }

        $agenda->update($validated);

        return redirect()->route('admin.agenda.index')
            ->with('success', 'Agenda berhasil diperbarui.');
    }

    public function destroy(Agenda $agenda)
    {
        // Hapus gambar
        if ($agenda->gambar) {
            Storage::disk('public')->delete($agenda->gambar);
        }

        $agenda->delete();

        return redirect()->route('admin.agenda.index')
            ->with('success', 'Agenda berhasil dihapus.');
    }
}
