<?php

namespace App\Exports;

use App\Models\BKLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BKLogExport implements FromView, ShouldAutoSize
{
    /**
     * Request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected Request $request;

    /**
     * Create a new export instance.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Generate the view for Excel export.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
    {
        $logs = BKLog::query()
            ->when($this->request->filled('nama'), fn($q) => $q->where('nama_murid', 'like', '%' . $this->request->nama . '%'))
            ->when($this->request->filled('minggu'), fn($q) => $q->where('minggu_ke', $this->request->minggu))
            ->when($this->request->filled('bulan'), fn($q) => $q->where('bulan', $this->request->bulan))
            ->get();

        $totalPoin = $logs->sum('poin');

        return view('bk.excel', [
            'logs' => $logs,
            'totalPoin' => $totalPoin
        ]);
    }
}
