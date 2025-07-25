<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data BK</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    <style>
        body {
            padding: 10px;
        }

        .form-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .btn-group {
            margin-top: 10px;
        }

        .text-center {
            padding: 30px 0 50px 0;
        }
    </style>
</head>

<body>

    <div class="container">
        <h3 class="text-center mb-4">CATATAN GURU BK</h3>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Filter --}}
        <form method="GET" id="filterForm" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="nama" value="{{ request('nama') }}" class="form-control"
                    placeholder="Filter Nama">
            </div>
            <div class="col-md-2">
                <input type="number" name="minggu" value="{{ request('minggu') }}" class="form-control"
                    placeholder="Minggu ke">
            </div>
            <div class="col-md-2">
                <select name="bulan" class="form-select">
                    <option value="">Bulan</option>
                    @foreach ([
                                'January' => 'Januari',
                                'February' => 'Februari',
                                'March' => 'Maret',
                                'April' => 'April',
                                'May' => 'Mei',
                                'June' => 'Juni',
                                'July' => 'Juli',
                                'August' => 'Agustus',
                                'September' => 'September',
                                'October' => 'Oktober',
                                'November' => 'November',
                                'December' => 'Desember',
                            ] as $en => $id)
                        <option value="{{ $en }}" {{ request('bulan') == $en ? 'selected' : '' }}>
                            {{ $id }}
                        </option>
                    @endforeach
                </select>

            </div>
            <div class="col-md-5 d-flex justify-content-end btn-group">
                <button type="submit" class="btn btn-secondary me-2">Terapkan Filter</button>

                <a href="{{ route('bk.index') }}" class="btn btn-outline-dark me-2">Hapus Filter</a>

                <button type="submit" class="btn btn-danger me-2"
                    onclick="submitToExport('{{ route('bk.export.pdf') }}')">Export PDF</button>

                <button type="submit" class="btn btn-success"
                    onclick="submitToExport('{{ route('bk.export.excel') }}')">Export Excel</button>
            </div>
        </form>


        {{-- Tabel Data --}}
        @if (request('nama') && $totalPoin !== null)
            <div class="alert alert-info">
                Total Poin untuk <strong>{{ request('nama') }}</strong>: <strong>{{ $totalPoin }}</strong>
            </div>
        @endif

        <table id="bkTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Minggu</th>
                    <th>Nama</th>
                    <th>Absen</th>
                    <th>Kelas</th>
                    <th>Catatan</th>
                    <th>Poin</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $i => $log)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($log->tanggal_input)->format('d/m/Y') }}</td>
                        <td>{{ $log->minggu_ke }}</td>
                        <td>{{ $log->nama_murid }}</td>
                        <td>{{ $log->nomor_absen }}</td>
                        <td>{{ $log->kelas }}</td>
                        <td>{{ $log->catatan }}</td>
                        <td>{{ $log->poin }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- JS CDN --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        function submitToExport(action) {
            const form = document.getElementById('filterForm');
            form.action = action;
        }
    </script>


</body>

</html>
