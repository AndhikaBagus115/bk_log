<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BKLog;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BKLogExport;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class BKLogController extends Controller
{
    public function index(Request $request)
    {
        $clientId = session('client_id'); // ⬅️ Ambil dari session login

        $logs = BKLog::query()
            ->where('client_id', $clientId) // ⬅️ Tambahkan filter client_id
            ->when($request->nama, fn($query) => $query->where('nama_murid', 'like', '%' . $request->nama . '%'))
            ->when($request->minggu, fn($query) => $query->where('minggu_ke', $request->minggu))
            ->when($request->bulan, fn($query) => $query->where('bulan', $request->bulan))
            ->latest()
            ->get();

        $totalPoin = $request->filled('nama') ? $logs->sum('poin') : null;

        return view('bk.index', compact('logs', 'totalPoin'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'nomor_absen' => 'required|integer',
            'nama_murid' => 'required|string',
            'kelas' => 'required|string',
            'catatan' => 'required|string',
            'poin' => 'required|integer',
        ]);

        $tanggal = now();
        $minggu_ke = ceil($tanggal->day / 7);
        $bulan = now()->locale('id')->isoFormat('MMMM');

        BKLog::create([
            'client_id' => session('client_id'), // ⬅️ WAJIB agar data tersimpan sesuai client
            'nomor_absen' => $request->nomor_absen,
            'nama_murid' => $request->nama_murid,
            'kelas' => $request->kelas,
            'catatan' => $request->catatan,
            'poin' => $request->poin,
            'tanggal_input' => $tanggal,
            'minggu_ke' => $minggu_ke,
            'bulan' => $bulan,
        ]);

        return back()->with('success', 'Data berhasil disimpan!');
    }

    public function exportExcel(Request $request)
    {
        $filename = 'bk_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new BKLogExport($request), $filename);
    }

    public function exportPDF(Request $request)
    {
        $clientId = session('client_id');

        $logs = BKLog::query()
            ->where('client_id', $clientId)
            ->when($request->nama, fn($q) => $q->where('nama_murid', 'like', '%' . $request->nama . '%'))
            ->when($request->minggu, fn($q) => $q->where('minggu_ke', $request->minggu))
            ->when($request->bulan, fn($q) => $q->where('bulan', $request->bulan))
            ->get();

        $totalPoin = $logs->sum('poin');

        // Ambil logo base64
        $path = public_path('images/logoMts.png');
        $logoBase64 = '';
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // Render HTML dengan logo
        $html = View::make('bk.pdf', [
            'logs' => $logs,
            'totalPoin' => $totalPoin,
            'logoBase64' => $logoBase64
        ])->render();

        // Buat PDF dari HTML
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Nama file dinamis
        $filename = 'bk_' . now()->format('Ymd_His') . '.pdf';

        // Kembalikan file PDF
        return response($dompdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
