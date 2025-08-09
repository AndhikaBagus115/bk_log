<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BKLog;
use Carbon\Carbon;

class BKLogApiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nomor_absen' => 'required|integer',
            'nama_murid' => 'required|string',
            'kelas' => 'required|string',
            'catatan' => 'required|string',
            'poin' => 'nullable|integer',
        ]);

        $clientId = $request->input('client_id');
        $tanggal = Carbon::now()->toDateString(); // YYYY-MM-DD
        $minggu_ke = ceil(Carbon::now()->day / 7);
        $bulan = Carbon::now()->locale('id')->isoFormat('MMMM');

        $bk = BKLog::create([
            'client_id' => $clientId,
            'nomor_absen' => $request->nomor_absen,
            'nama_murid' => $request->nama_murid,
            'kelas' => $request->kelas,
            'catatan' => $request->catatan,
            'poin' => $request->poin,
            'tanggal_input' => $tanggal,
            'minggu_ke' => $minggu_ke,
            'bulan' => $bulan,
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan.',
            'data' => $bk
        ], 201);
    }


    public function index(Request $request)
    {
        $clientId = $request->input('client_id');

        $logs = BKLog::where('client_id', $clientId)->latest()->get();

        return response()->json([
            'message' => 'Data berhasil diambil',
            'data' => $logs
        ]);
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nomor_absen' => 'required|integer',
            'nama_murid' => 'required|string',
            'kelas' => 'required|string',
            'catatan' => 'required|string',
            'poin' => 'required|integer',
        ]);

        // Ambil client_id dari middleware
        $clientId = $request->input('client_id');

        $bk = BKLog::where('id', $id)->where('client_id', $clientId)->first();

        if (!$bk) {
            return response()->json(['message' => 'Data tidak ditemukan atau tidak diizinkan.'], 404);
        }

        $bk->update($validated);

        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }


    public function destroy(Request $request, $id)
    {
        // Ambil client_id dari middleware
        $clientId = $request->input('client_id');

        $bk = BKLog::where('id', $id)->where('client_id', $clientId)->first();

        if (!$bk) {
            return response()->json(['message' => 'Data tidak ditemukan atau tidak diizinkan.'], 404);
        }

        $bk->delete();

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
