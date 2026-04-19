<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WilayahKabkota;
use App\Models\WilayahKecamatan;
use App\Models\WilayahKelurahan;
use Illuminate\Http\{Request, JsonResponse};

class WilayahController extends Controller
{
    /**
     * Daftar kab/kota berdasarkan provinsi yang dipilih.
     * Endpoint: GET /admin/api/wilayah/kabkota?provinsi_id=1
     */
    public function kabkota(Request $request): JsonResponse
    {
        $request->validate(['provinsi_id' => 'required|integer|exists:wilayah_provinsi,id']);

        $data = WilayahKabkota::where('provinsi_id', $request->provinsi_id)
            ->select('id', 'nama', 'kode')
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    /**
     * Daftar kecamatan berdasarkan kabkota yang dipilih.
     */
    public function kecamatan(Request $request): JsonResponse
    {
        $request->validate(['kabkota_id' => 'required|integer|exists:wilayah_kabkota,id']);

        $data = WilayahKecamatan::where('kabkota_id', $request->kabkota_id)
            ->select('id', 'nama', 'kode')
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }

    /**
     * Daftar kelurahan/desa berdasarkan kecamatan yang dipilih.
     */
    public function kelurahan(Request $request): JsonResponse
    {
        $request->validate(['kecamatan_id' => 'required|integer|exists:wilayah_kecamatan,id']);

        $data = WilayahKelurahan::where('kecamatan_id', $request->kecamatan_id)
            ->select('id', 'nama', 'kode')
            ->orderBy('nama')
            ->get();

        return response()->json($data);
    }
}
