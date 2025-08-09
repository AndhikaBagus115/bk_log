<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\BKLog;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BKLogExport;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BKLogController extends Controller
{
    public function index(Request $request)
    {
        $clientId = Auth::guard('client')->id();
        $perPage = $request->input('per_page', 10);

        // ... (kode query Anda tidak berubah) ...
        $query = BKLog::query()
            ->where('client_id', $clientId)
            ->when($request->nama, function ($q, $nama) {
                return $q->where('nama_murid', 'like', '%' . $nama . '%');
            })
            ->when($request->minggu, function ($q, $minggu) {
                return $q->where('minggu_ke', $minggu);
            })
            ->when($request->bulan, function ($q, $bulan) {
                $monthNumber = date('m', strtotime($bulan));
                return $q->whereMonth('tanggal_input', $monthNumber);
            });

        $totalPoin = null;
        if ($request->filled('nama')) {
            $totalPoin = (clone $query)->sum('poin');
        }

        $logs = $query->latest('tanggal_input')->paginate($perPage)->appends($request->query());

        // Ambil data client yang sedang login
        $client = Auth::guard('client')->user();
        // Ambil path logonya, atau null jika tidak ada
        $logoPath = $client ? $client->logo : null;

        // Kirim semua variabel ke view, termasuk $logoPath
        return view('bk.index', compact('logs', 'totalPoin', 'logoPath'));
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
            'client_id' => Auth::guard('client')->id(),
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
        // 1. Ambil seluruh objek client yang sedang login
        $client = Auth::guard('client')->user();

        // 2. Jika tidak ada client, hentikan proses dengan pesan error
        if (!$client) {
            return redirect()->back()->with('error', 'Gagal mengekspor, data client tidak ditemukan.');
        }

        $filename = 'bk_' . now()->format('Ymd_His') . '.xlsx';

        // 3. Kirim $request dan SELURUH OBJEK $client ke dalam BKLogExport
        return Excel::download(new BKLogExport($request, $client), $filename);
    }


    public function exportPDF(Request $request)
    {
        $clientId = Auth::guard('client')->id();
        $client = Auth::guard('client')->user();

        $logs = \App\Models\BKLog::query()
            ->where('client_id', $clientId)
            ->when($request->nama, fn($q) => $q->where('nama_murid', 'like', '%' . $request->nama . '%'))
            ->when($request->minggu, fn($q) => $q->where('minggu_ke', $request->minggu))
            ->when($request->bulan, function ($q, $bulan) {
                $monthNumber = date('m', strtotime($bulan));
                return $q->whereMonth('tanggal_input', $monthNumber);
            })
            ->get();

        $totalPoin = $logs->sum('poin');

        $logoBase64 = null;
        if ($client && $client->logo && Storage::exists($client->logo)) {
            $path = storage_path('app/' . $client->logo);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $html = View::make('bk.pdf', [
            'logs' => $logs,
            'totalPoin' => $totalPoin,
            'logoBase64' => $logoBase64,
        ])->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->set_option('defaultFont', 'Arial');
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'bk_' . now()->format('Ymd_His') . '.pdf';

        return response($dompdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
