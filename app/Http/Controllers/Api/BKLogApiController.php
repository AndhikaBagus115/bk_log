<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BKLog;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class BKLogApiController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nomor_absen' => 'required|integer',
                'nama_murid' => 'required|string',
                'kelas' => 'required|string',
                'catatan' => 'required|string',
                'poin' => 'nullable|integer',
                'tindak_lanjut' => 'nullable|string'
            ]);

            $clientId = $request->input('client_id');

            $bk = BKLog::create(array_merge($validatedData, [
                'client_id' => $clientId,
                'tanggal_input' => Carbon::now()->toDateString(),
                'minggu_ke' => ceil(Carbon::now()->day / 7),
                'bulan' => Carbon::now()->locale('id')->isoFormat('MMMM'),
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
                'data' => $bk
            ], 201);

        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Data tidak valid', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('API Store Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server.'], 500);
        }
    }

    public function index(Request $request)
    {
        $clientId = $request->input('client_id');
        $logs = BKLog::where('client_id', $clientId)->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data' => $logs
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            // PERBAIKAN: Aturan validasi disamakan dengan store()
            $validatedData = $request->validate([
                'nomor_absen' => 'required|integer',
                'nama_murid' => 'required|string',
                'kelas' => 'required|string',
                'catatan' => 'required|string',
                'poin' => 'nullable|integer',
                'tindak_lanjut' => 'nullable|string'
            ]);

            $clientId = $request->input('client_id');
            $bk = BKLog::where('id', $id)->where('client_id', $clientId)->firstOrFail();

            $bk->update($validatedData);

            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui.']);

        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Data tidak valid', 'errors' => $e->errors()], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan atau tidak diizinkan.'], 404);
        } catch (\Exception $e) {
            Log::error('API Update Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server.'], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $clientId = $request->input('client_id');
            $bk = BKLog::where('id', $id)->where('client_id', $clientId)->firstOrFail();
            $bk->delete();

            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan atau tidak diizinkan.'], 404);
        } catch (\Exception $e) {
            Log::error('API Destroy Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server.'], 500);
        }
    }
}
