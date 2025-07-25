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
            'poin' => 'required|integer',
        ]);

        $tanggal = Carbon::now();
        $minggu_ke = ceil($tanggal->day / 7);

        $bk = BKLog::create([
            'nomor_absen' => $request->nomor_absen,
            'nama_murid' => $request->nama_murid,
            'kelas' => $request->kelas,
            'catatan' => $request->catatan,
            'poin' => $request->poin,
            'tanggal_input' => $tanggal,
            'minggu_ke' => $minggu_ke,
            'bulan' => $tanggal->locale('id')->isoFormat('MMMM'),
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan.',
            'data' => $bk
        ], 201);
    }
}

