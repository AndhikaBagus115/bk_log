<table style="width: 100%;">
    <tr>
        <td style="width: 80px; padding: 0; display: flex; justify-content: center; align-items: center;">
            <img src="{{ public_path('images/logoMts.png') }}" width="70" style="display: block;">
        </td>
        <td colspan="6" style="text-align: center; vertical-align: middle; height: 80px;">
            <div style="line-height: 1.4;">
                <strong style="font-size: 16px;">MTS SUNAN GUNUNG JATI</strong><br>
                <span style="font-size: 12px;">
                    Jl. PGA No.05, Gurah I, Gurah, Kec. Gurah,Kabupaten Kediri, Jawa Timur 64181
                </span>
            </div>
        </td>
    </tr>
</table>

<br>

<table style="width: 100%;">
    <tr>
        <td colspan="7" style="text-align: center; font-size: 16px; font-weight: bold;">
            Laporan Data BK
        </td>
    </tr>
</table>

<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid #000; text-align: center;">No</th>
            <th style="border: 1px solid #000; text-align: center;">Tanggal</th>
            <th style="border: 1px solid #000; text-align: center;">Nama Murid</th>
            <th style="border: 1px solid #000; text-align: center;">No Absen</th>
            <th style="border: 1px solid #000; text-align: center;">Kelas</th>
            <th style="border: 1px solid #000; text-align: center;">Catatan Masalah</th>
            <th style="border: 1px solid #000; text-align: center;">Poin</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($logs as $i => $log)
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $i + 1 }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ \Carbon\Carbon::parse($log->tanggal_input)->format('d M Y') }}</td>
                <td style="border: 1px solid #000;">{{ $log->nama_murid }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $log->nomor_absen }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $log->kelas }}</td>
                <td style="border: 1px solid #000;">{{ $log->catatan }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $log->poin }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="6" style="border: 1px solid #000; text-align: center; font-weight: bold;">Total Poin</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $totalPoin }}</td>
        </tr>
    </tbody>
</table>

<br>

<table style="width: 100%;">
    <tr>
        <td colspan="7" style="text-align: center; font-size: 11px;">
            Dicetak oleh sistem BK otomatis - {{ now()->translatedFormat('d F Y H:i') }}
        </td>
    </tr>
</table>
