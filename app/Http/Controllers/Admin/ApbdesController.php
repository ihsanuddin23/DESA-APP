<?php
// app/Http/Controllers/Admin/ApbdesController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apbdes;
use App\Models\ApbdesItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApbdesController extends Controller
{
    // ══════════════════════════════════════════════════════════════════════════
    // MASTER APBDes (per tahun)
    // ══════════════════════════════════════════════════════════════════════════

    public function index(Request $request)
    {
        $query = Apbdes::query()->orderByDesc('tahun');

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $apbdes = $query->paginate(10)->withQueryString();
        $tahunList = Apbdes::distinct()->pluck('tahun')->sort()->reverse();

        return view('admin.apbdes.index', compact('apbdes', 'tahunList'));
    }

    public function create()
    {
        return view('admin.apbdes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => ['required', 'integer', 'min:2020', 'max:2099', 'unique:apbdes,tahun'],
            'status' => ['required', Rule::in(['draft', 'aktif', 'selesai'])],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);

        Apbdes::create($validated);

        return redirect()->route('admin.apbdes.index')
            ->with('success', 'APBDes tahun ' . $validated['tahun'] . ' berhasil ditambahkan.');
    }

    public function show(Apbdes $apbdes)
    {
        $apbdes->load(['pendapatan', 'belanja', 'pembiayaan']);

        return view('admin.apbdes.show', compact('apbdes'));
    }

    public function edit(Apbdes $apbdes)
    {
        return view('admin.apbdes.edit', compact('apbdes'));
    }

    public function update(Request $request, Apbdes $apbdes)
    {
        $validated = $request->validate([
            'tahun' => ['required', 'integer', 'min:2020', 'max:2099', Rule::unique('apbdes', 'tahun')->ignore($apbdes->id)],
            'status' => ['required', Rule::in(['draft', 'aktif', 'selesai'])],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);

        $apbdes->update($validated);

        return redirect()->route('admin.apbdes.index')
            ->with('success', 'APBDes tahun ' . $validated['tahun'] . ' berhasil diperbarui.');
    }

    public function destroy(Apbdes $apbdes)
    {
        $tahun = $apbdes->tahun;
        $apbdes->delete();

        return redirect()->route('admin.apbdes.index')
            ->with('success', 'APBDes tahun ' . $tahun . ' berhasil dihapus.');
    }

    // ══════════════════════════════════════════════════════════════════════════
    // ITEM APBDes (pendapatan, belanja, pembiayaan)
    // ══════════════════════════════════════════════════════════════════════════

    public function itemIndex(Request $request, Apbdes $apbdes)
    {
        $jenis = $request->get('jenis', 'pendapatan');

        $items = $apbdes->items()
            ->where('jenis', $jenis)
            ->orderBy('urutan')
            ->paginate(20)
            ->withQueryString();

        return view('admin.apbdes.items.index', compact('apbdes', 'items', 'jenis'));
    }

    public function itemCreate(Apbdes $apbdes, Request $request)
    {
        $jenis = $request->get('jenis', 'pendapatan');
        return view('admin.apbdes.items.create', compact('apbdes', 'jenis'));
    }

    public function itemStore(Request $request, Apbdes $apbdes)
    {
        $validated = $request->validate([
            'jenis' => ['required', Rule::in(['pendapatan', 'belanja', 'pembiayaan'])],
            'kode_rekening' => ['nullable', 'string', 'max:50'],
            'uraian' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'anggaran' => ['required', 'numeric', 'min:0'],
            'realisasi' => ['nullable', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'urutan' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['apbdes_id'] = $apbdes->id;
        $validated['realisasi'] = $validated['realisasi'] ?? 0;
        $validated['urutan'] = $validated['urutan'] ?? ($apbdes->items()->where('jenis', $validated['jenis'])->max('urutan') + 1);

        ApbdesItem::create($validated);

        return redirect()->route('admin.apbdes.items.index', ['apbdes' => $apbdes, 'jenis' => $validated['jenis']])
            ->with('success', 'Item ' . ucfirst($validated['jenis']) . ' berhasil ditambahkan.');
    }

    public function itemEdit(Apbdes $apbdes, ApbdesItem $item)
    {
        return view('admin.apbdes.items.edit', compact('apbdes', 'item'));
    }

    public function itemUpdate(Request $request, Apbdes $apbdes, ApbdesItem $item)
    {
        $validated = $request->validate([
            'jenis' => ['required', Rule::in(['pendapatan', 'belanja', 'pembiayaan'])],
            'kode_rekening' => ['nullable', 'string', 'max:50'],
            'uraian' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'anggaran' => ['required', 'numeric', 'min:0'],
            'realisasi' => ['nullable', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'urutan' => ['nullable', 'integer', 'min:0'],
        ]);

        $item->update($validated);

        return redirect()->route('admin.apbdes.items.index', ['apbdes' => $apbdes, 'jenis' => $validated['jenis']])
            ->with('success', 'Item berhasil diperbarui.');
    }

    public function itemDestroy(Apbdes $apbdes, ApbdesItem $item)
    {
        $jenis = $item->jenis;
        $item->delete();

        return redirect()->route('admin.apbdes.items.index', ['apbdes' => $apbdes, 'jenis' => $jenis])
            ->with('success', 'Item berhasil dihapus.');
    }

    // ══════════════════════════════════════════════════════════════════════════
    // PUBLIC VIEW
    // ══════════════════════════════════════════════════════════════════════════

    public function public(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        $apbdes = Apbdes::where('tahun', $tahun)
            ->where('status', '!=', 'draft')
            ->first();

        $tahunList = Apbdes::where('status', '!=', 'draft')
            ->distinct()
            ->pluck('tahun')
            ->sort()
            ->reverse();

        return view('apbdes', compact('apbdes', 'tahun', 'tahunList'));
    }
}