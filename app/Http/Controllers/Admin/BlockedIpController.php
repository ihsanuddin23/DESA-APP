<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\View\View;

class GaleriController extends Controller
{
    public function index(Request $request): View
    {
        $galeri = Galeri::orderByDesc('created_at')->paginate(15);
        return view('admin.galeri.index', compact('galeri'));
    }

    public function create(): View
    {
        return view('admin.galeri.create');
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('admin.galeri.index')->with('info', 'Fitur masih dalam pengembangan.');
    }

    public function edit(Galeri $galeri): View
    {
        return view('admin.galeri.edit', compact('galeri'));
    }

    public function update(Request $request, Galeri $galeri): RedirectResponse
    {
        return redirect()->route('admin.galeri.index')->with('info', 'Fitur masih dalam pengembangan.');
    }

    public function destroy(Galeri $galeri): RedirectResponse
    {
        $galeri->delete();
        return redirect()->route('admin.galeri.index')->with('success', 'Galeri dihapus.');
    }
}