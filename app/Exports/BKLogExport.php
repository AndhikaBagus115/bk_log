<?php

namespace App\Exports;

use App\Models\BKLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BKLogExport implements FromCollection, WithHeadings, WithEvents, WithDrawings, ShouldAutoSize, WithCustomStartCell, WithColumnFormatting
{
    protected $request;
    protected $client;

    public function __construct(Request $request, $client)
    {
        $this->request = $request;
        $this->client  = $client;
    }

    /**
     * Menentukan sel di mana tabel akan dimulai.
     * Sesuai gambar, tabel dimulai di baris 9.
     */
    public function startCell(): string
    {
        return 'A9';
    }

    /**
     * Mengatur format spesifik untuk kolom tertentu.
     */
    public function columnFormats(): array
    {
        return [
            // Paksa kolom G (Poin) untuk selalu menampilkan angka, termasuk 0.
            'G' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    /**
     * Mengambil data untuk diekspor.
     */
    public function collection()
    {
        $query = BKLog::where('client_id', $this->client->id);

        if ($this->request->filled('minggu')) {
            $query->where('minggu', $this->request->minggu);
        }

        if ($this->request->filled('bulan')) {
            $query->whereMonth('tanggal_input', $this->request->bulan);
        }

        if ($this->request->filled('nama')) { // <-- disesuaikan dengan nama field di form
            $query->where('nama_murid', 'like', '%' . $this->request->nama . '%');
        }

        $logs = $query->orderBy('tanggal_input', 'DESC')->get();

        return $logs->map(function ($log, $index) {
            return [
                'No'         => $index + 1,
                'Tanggal'    => \Carbon\Carbon::parse($log->tanggal_input)->format('d M Y'),
                'Nama Murid' => $log->nama_murid,
                'No Absen'   => $log->nomor_absen,
                'Kelas'      => $log->kelas,
                'Catatan'    => $log->catatan,
                'Poin'       => ($log->poin === null || $log->poin === '') ? 0 : (int) $log->poin,
                'Tindak Lanjut' => $log->tindak_lanjut ?? ' ',
            ];
        });
    }



    /**
     * Mendefinisikan header untuk tabel.
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Nama Murid',
            'No Absen',
            'Kelas',
            'Catatan',
            'Poin',
            'Tindak Lanjut',
        ];
    }

    /**
     * Menambahkan logo sekolah.
     */
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo Sekolah');
        $drawing->setDescription('Logo Sekolah');

        // Pastikan path ke logo valid
        $logoPath = storage_path('app/' . $this->client->logo);
        if (!file_exists($logoPath) || is_dir($logoPath)) {
            $logoPath = public_path('images/default-logo.png'); // Sediakan logo default jika tidak ada
        }

        $drawing->setPath($logoPath);
        $drawing->setHeight(80); // Atur tinggi logo
        $drawing->setCoordinates('B1'); // Posisi logo di pojok kiri atas

        return $drawing;
    }

    /**
     * Menerapkan styling dan layout kustom setelah sheet dibuat.
     */
    // Di bagian registerEvents()
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = 'H'; // Kolom terakhir

                // --- KOP SURAT ---
                // Merge untuk nama sekolah
                $sheet->mergeCells('C1:' . $lastColumn . '1');
                $sheet->setCellValue('C1', 'MTS SUNAN GUNUNG JATI');
                $sheet->getStyle('C1')->getFont()->setBold(true)->setSize(20);
                $sheet->getStyle('C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Merge untuk alamat
                $sheet->mergeCells('C3:' . $lastColumn . '3');
                $sheet->setCellValue('C3', 'Jl. PGA No.05, Gurah I, Gurah, Kec. Gurah, Kabupaten Kediri, Jawa Timur 64181');
                $sheet->getStyle('C3')->getFont()->setSize(12);
                $sheet->getStyle('C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Garis pemisah tebal
                $sheet->mergeCells('A4:' . $lastColumn . '4');
                $sheet->getStyle('A4:' . $lastColumn . '4')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THICK);

                // --- JUDUL LAPORAN ---
                $sheet->mergeCells('A6:' . $lastColumn . '6');
                $sheet->setCellValue('A6', 'LAPORAN DATA BIMBINGAN KONSELING (BK)');
                $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // --- HEADER TABEL ---
                $tableHeaderRange = 'A9:' . $lastColumn . '9';
                $sheet->getStyle($tableHeaderRange)->getFont()->setBold(true);
                $sheet->getStyle($tableHeaderRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle($tableHeaderRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // Border semua tabel
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A9:' . $lastColumn . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // Rata tengah untuk kolom tertentu
                $sheet->getStyle('A10:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D10:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('E10:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('G10:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                for ($row = 10; $row <= $lastRow; $row++) { // Asumsi data dimulai dari baris 10
                    $cell = 'G' . $row;
                    if ($sheet->getCell($cell)->getValue() === null || $sheet->getCell($cell)->getValue() === '') {
                        $sheet->setCellValue($cell, 0);
                    }
                }

                // --- TOTAL POIN ---
                $collection = $this->collection();
                if ($collection->isNotEmpty()) {
                    $totalRow = $lastRow + 1;
                    // Gabungkan 6 kolom pertama
                    $sheet->mergeCells("A{$totalRow}:F{$totalRow}");
                    $sheet->setCellValue("A{$totalRow}", 'Total Poin');
                    $sheet->getStyle("A{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Letakkan nilai total di kolom G (Poin)
                    $sheet->setCellValue("G{$totalRow}", $collection->sum('Poin'));

                    // Kosongkan sel di kolom H (Tindak Lanjut)
                    $sheet->setCellValue("H{$totalRow}", '');

                    // Beri border dan style
                    $sheet->getStyle("A{$totalRow}:H{$totalRow}")->getFont()->setBold(true);
                    $sheet->getStyle("A{$totalRow}:H{$totalRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    $sheet->getStyle("G{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


                    // --- TANDA TANGAN ---
                    $signatureRow = $totalRow + 4;
                    $sheet->setCellValue("H{$signatureRow}", 'Kediri, ' . now()->translatedFormat('d F Y'));
                    $sheet->getStyle("H{$signatureRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->setCellValue("H" . ($signatureRow + 1), 'Guru BK');
                    $sheet->getStyle("H" . ($signatureRow + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->setCellValue("H" . ($signatureRow + 5), 'Moh. Edi Kurniawan');
                    $sheet->getStyle("H" . ($signatureRow + 5))->getFont()->setBold(true);
                    $sheet->getStyle("H" . ($signatureRow + 5))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            }
        ];
    }
}
