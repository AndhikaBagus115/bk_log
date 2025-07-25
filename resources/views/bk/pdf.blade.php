
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
            margin: 0;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .logo {
            width: 80px;
            height: auto;
            margin-right: 15px;
        }

        .header-text {
            text-align: left;
            line-height: 1.4;
        }

        .header-text .title {
            font-size: 16px;
            font-weight: bold;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table,
        .table th,
        .table td {
            border: 1px solid black;
        }

        .table th,
        .table td {
            padding: 5px;
            text-align: center;
        }

        .footer {
            position: fixed;
            bottom: 10px;
            width: 100%;
            font-style: italic;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>

<body>

    <table width="100%" style="margin-bottom: 10px;">
        <tr>
            <td width="80">
                <img src="{{ $logoBase64 }}" width="70" alt="Logo Sekolah">
            </td>
            <td>
                <div style="font-size: 16px; font-weight: bold;">MTS SUNAN GUNUNG JATI</div>
                <div style="font-size: 12px;">
                    Alamat: Jl. PGA No.05, Gurah I, Gurah, Kec. Gurah,<br>
                    Kabupaten Kediri, Jawa Timur 64181
                </div>
            </td>
        </tr>
    </table>


    <h3 style="text-align: center;">Laporan Data BK</h3>

    <!-- TABEL -->
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
            @foreach ($logs as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                    <td>{{ $item->nama_murid }}</td>
                    <td>{{ $item->nomor_absen }}</td>
                    <td>{{ $item->kelas }}</td>
                    <td>{{ $item->catatan }}</td>
                    <td>{{ $item->poin }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6"><strong>Total Poin</strong></td>
                <td><strong>{{ $totalPoin }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        Dicetak oleh sistem BK otomatis - {{ now()->format('d F Y H:i') }}
    </div>

</body>

</html>
