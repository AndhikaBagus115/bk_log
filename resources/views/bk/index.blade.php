<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catatan Guru BK</title>

    {{-- Bootstrap & Ikon --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- CSS Kustom --}}
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .card-header.bg-primary {
            color: white;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            padding: 1rem 0;
        }

        .pagination {
            margin: 0;
        }

        .pagination .page-item .page-link {
            color: #0d6efd;
            border-radius: 50%;
            margin: 0 5px;
            border: 1px solid #dee2e6;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .pagination .page-item .page-link:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
            color: #0056b3;
        }

        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
            transform: translateY(-2px);
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .pagination .page-link .bi {
            font-size: 1.2rem;
        }
    </style>
</head>

<body>

    <div class="container">

        {{-- Menampilkan logo menggunakan URL dari helper asset() --}}
        @if (isset($logoPath) && $logoPath)
            <div class="text-center mb-4">
                {{-- Kita ganti 'public/' menjadi 'storage/' untuk membuat URL yang benar --}}
                <img src="{{ asset(str_replace('public/', 'storage/', $logoPath)) }}" alt="Logo Instansi"
                    style="max-height: 100px; object-fit: contain;">
            </div>
        @endif

        {{-- Header Halaman dan Tombol Logout --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Catatan Guru BK</h2>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-logout">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>

        {{-- Bagian Upload Logo --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Pengaturan Logo</h5>
            </div>
            <div class="card-body">
                <div class="mb-3" style="max-width: 550px;">

                    <div id="client-logo-preview-wrapper" class="d-none position-relative mb-2"
                        style="display: inline-block; border: 1px solid #ddd; padding: 5px; border-radius: 8px; max-height: 100px;">
                        <button id="cancel-preview-btn" type="button" class="btn-close" aria-label="Close"
                            style="position: absolute; top: -10px; right: -10px; z-index: 10; background-color: white; border-radius: 50%;"></button>
                        <img id="client-preview-img" src="#" alt="Pratinjau Logo"
                            style="max-height: 80px; object-fit: contain;">
                    </div>

                    <p>Pilih file baru atau hapus saat ini:</p>

                    <div class="d-flex align-items-start" style="gap: 10px;">

                        <form method="POST" action="{{ route('client.upload.logo') }}" enctype="multipart/form-data"
                            class="flex-grow-1">
                            @csrf
                            <div class="input-group">
                                <input type="file" name="logo" id="logo-input" class="form-control"
                                    accept="image/*" required>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i>
                                    Unggah</button>
                            </div>
                            @if ($errors->has('logo'))
                                <div class="text-danger mt-1"><small>{{ $errors->first('logo') }}</small></div>
                            @endif
                        </form>

                        @if (isset($logoPath) && $logoPath)
                            <form method="POST" action="{{ route('client.delete.logo') }}"
                                onsubmit="return confirm('Anda yakin ingin menghapus logo ini secara permanen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger text-nowrap">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Notifikasi Sukses atau Error --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Form Filter Data --}}
        <div class="card mb-4">
            <div class="card-header bg-primary">
                <h5 class="mb-0 text-white"><i class="bi bi-funnel-fill"></i> Filter Data</h5>
            </div>
            <form method="GET" id="filterForm">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6 col-lg-2">
                            <label for="per_page" class="form-label">Tampilkan Data:</label>
                            <select name="per_page" id="per_page" class="form-select" onchange="this.form.submit()">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="nama" class="form-label">Filter Nama Murid:</label>
                            <input type="text" name="nama" id="nama" value="{{ request('nama') }}"
                                class="form-control" placeholder="Ketik nama murid...">
                        </div>
                        <div class="col-md-6 col-lg-2">
                            <label for="minggu" class="form-label">Minggu ke:</label>
                            <input type="number" name="minggu" id="minggu" value="{{ request('minggu') }}"
                                class="form-control" placeholder="Contoh: 1">
                        </div>
                        <div class="col-md-6 col-lg-2">
                            <label for="bulan" class="form-label">Bulan:</label>
                            <select name="bulan" id="bulan" class="form-select">
                                <option value="">Semua Bulan</option>
                                @foreach (['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'] as $en => $id)
                                    <option value="{{ $en }}"
                                        {{ request('bulan') == $en ? 'selected' : '' }}>{{ $id }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end bg-light">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Terapkan
                        Filter</button>
                    <a href="{{ route('bk.index') }}" class="btn btn-secondary"><i
                            class="bi bi-arrow-clockwise"></i> Hapus Filter</a>
                    <button type="button" class="btn btn-danger"
                        onclick="submitToExport('{{ route('bk.export.pdf') }}')"><i
                            class="bi bi-file-earmark-pdf-fill"></i> Export PDF</button>
                    <button type="button" class="btn btn-success"
                        onclick="submitToExport('{{ route('bk.export.excel') }}')"><i
                            class="bi bi-file-earmark-excel-fill"></i> Export Excel</button>
                </div>
            </form>
        </div>

        {{-- Informasi Total Poin --}}
        @if (request('nama') && $totalPoin !== null)
            <div class="alert alert-info">
                Total Poin untuk <strong>{{ request('nama') }}</strong>: <strong
                    class="fs-5">{{ $totalPoin }}</strong>
            </div>
        @endif

        {{-- Tabel Data --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Minggu</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Absen</th>
                                <th scope="col">Kelas</th>
                                <th scope="col">Catatan</th>
                                <th scope="col">Poin</th>
                                <th scope="col">Tindak Lanjut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $index => $log)
                                <tr>
                                    <td>{{ $logs->firstItem() + $index }}</td>
                                    <td>{{ \Carbon\Carbon::parse($log->tanggal_input)->isoFormat('DD MMMM YYYY') }}
                                    </td>
                                    <td>{{ $log->minggu_ke }}</td>
                                    <td>{{ $log->nama_murid }}</td>
                                    <td>{{ $log->nomor_absen }}</td>
                                    <td>{{ $log->kelas }}</td>
                                    <td>{{ $log->catatan }}</td>
                                    <td>{{ $log->poin }}</td>
                                    <td>{{ $log->tindak_lanjut ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <h5>Tidak ada data yang ditemukan.</h5>
                                        <p class="text-muted">Coba ubah atau hapus filter yang sedang aktif.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginasi dan Info Data --}}
                <div class="pagination-container">
                    <div class="text-muted">
                        @if ($logs->total() > 0)
                            Menampilkan <b>{{ $logs->firstItem() }}</b> sampai <b>{{ $logs->lastItem() }}</b> dari
                            <b>{{ $logs->total() }}</b> data
                        @else
                            Tidak ada data untuk ditampilkan
                        @endif
                    </div>
                    <div>
                        {{-- Render Paginasi dengan Ikon Kustom --}}
                        {{ $logs->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        function submitToExport(action) {
            const form = document.getElementById('filterForm');
            // Simpan action asli untuk dikembalikan
            const originalAction = form.action;
            form.action = action;
            form.target = '_blank'; // Buka di tab baru agar tidak meninggalkan halaman
            form.submit();
            // Kembalikan action dan target ke semula
            form.action = originalAction;
            form.target = '_self';
        }

        // Fungsi untuk pratinjau logo di sisi klien
        document.addEventListener('DOMContentLoaded', function() {
            const logoInput = document.getElementById('logo-input');
            const previewWrapper = document.getElementById('client-logo-preview-wrapper');
            const previewImage = document.getElementById('client-preview-img');
            const cancelBtn = document.getElementById('cancel-preview-btn');

            // 1. Tampilkan pratinjau saat file dipilih
            logoInput.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewWrapper.classList.remove('d-none'); // Tampilkan wrapper pratinjau
                    };

                    reader.readAsDataURL(file);
                }
            });

            // 2. Batalkan pratinjau saat tombol 'X' diklik
            cancelBtn.addEventListener('click', function() {
                previewWrapper.classList.add('d-none'); // Sembunyikan wrapper pratinjau
                previewImage.src = ''; // Hapus sumber gambar
                logoInput.value = ""; // Reset input file, ini PENTING!
            });
        });
    </script>

</body>

</html>
