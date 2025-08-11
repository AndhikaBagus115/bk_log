<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data BK</title>
    <style>
        @page {
            /* Memberi ruang di bawah untuk footer */
            margin: 100px 25px 150px 25px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }

        header {
            position: fixed;
            top: -80px; /* Tarik header ke area margin atas */
            left: 0px;
            right: 0px;
            height: 90px;
        }

        footer {
            position: fixed;
            bottom: -30px; /* Ditarik ke area margin bawah */
            left: 0px;
            right: 0px;
            height: 140px;
        }

        .footer-info {
            text-align: center;
            font-size: 9px;
            font-style: italic;
            margin-top: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;

        }
        .table th, .table td {
            border: 1px solid black;
            padding: 5px;
        }
        .table th {
            text-align: center;
            background-color: #f2f2f2;
        }

        .table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        .table tbody tr:nth-child(20n) {
            page-break-after: always;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }

        .signature-table {
            width: 100%;
        }
        .signature-table td {
            width: 100%; /* Ubah agar satu kolom */
            text-align: right; /* Rata kanan */
            border: none;
            padding: 20px 20px 50px 20px; /* Tambahkan padding bawah untuk ruang tanda tangan */

        }
    </style>
</head>
<body>

    <header>
        <table width="100%" style="border-bottom: 2px solid black; padding-bottom: 10px;">
            <tr>
                <td style="width: 80px; padding: 0; text-align: center; border: none;">
                    @if (isset($logoBase64) && $logoBase64)
                        <img src="{{ $logoBase64 }}" width="70">
                    @endif
                </td>
                <td style="text-align: center; border: none;">
                    <div style="font-size: 18px; font-weight: bold;">MTS SUNAN GUNUNG JATI</div>
                    <div style="font-size: 12px;">
                        Jl. PGA No.05, Gurah I, Gurah, Kec. Gurah, Kabupaten Kediri, Jawa Timur 64181
                    </div>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <table class="signature-table">
            <tr>
                <td>
                    Kediri, {{ now()->translatedFormat('d F Y') }}<br>
                    Guru BK
                    <br><br><br><br><br>
                    <strong style="text-decoration: underline;">Moh. Edi Kurniawan</strong><br>
                </td>
            </tr>
        </table>
        <div class="footer-info">
            Dicetak oleh sistem BK otomatis - {{ now()->translatedFormat('d F Y H:i') }}
        </div>
    </footer>

    <main>
        <h3 class="text-center" style="margin-top: 20px;">Laporan Data Bimbingan Konseling (BK)</h3>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Murid</th>
                    <th>No Absen</th>
                    <th>Kelas</th>
                    <th>Catatan Masalah</th>
                    <th>Poin</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $i => $log)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($log->tanggal_input)->isoFormat('DD MMM YYYY') }}</td>
                        <td class="text-left">{{ $log->nama_murid }}</td>
                        <td class="text-center">{{ $log->nomor_absen }}</td>
                        <td class="text-center">{{ $log->kelas }}</td>
                        <td class="text-left">{{ $log->catatan }}</td>
                        <td class="text-center font-bold">{{ $log->poin }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 20px;">Tidak ada data yang cocok dengan filter.</td>
                    </tr>
                @endforelse
                @if(count($logs) > 0)
                <tr>
                    <td colspan="6" class="text-center font-bold">Total Poin</td>
                    <td class="text-center font-bold">{{ $totalPoin }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </main>

</body>
</html>
