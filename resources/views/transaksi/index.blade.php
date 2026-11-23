<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Penjualan Jersey Barcelona 2026</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-light">

<div class="container py-4">

    <div class="p-4 mb-4 text-white rounded-3"
         style="background: linear-gradient(120deg, #102b57, #a50044);">
        <h1 class="h3 mb-1">FC Barcelona</h1>
        <p class="mb-0">
            Dashboard Penjualan Jersey Tahun 2026
        </p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM INPUT TRANSAKSI --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <strong>Input Transaksi Penjualan</strong>
        </div>

        <div class="card-body">
            <form action="{{ route('transaksi.store') }}" method="POST">
                @csrf

                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="tanggal_transaksi"
                               class="form-label">
                            Tanggal Transaksi
                        </label>

                        <input type="date"
                               id="tanggal_transaksi"
                               name="tanggal_transaksi"
                               class="form-control"
                               min="2026-01-01"
                               max="2026-12-31"
                               value="{{ old('tanggal_transaksi') }}"
                               required>
                    </div>

                    <div class="col-md-4">
                        <label for="jumlah_penjualan"
                               class="form-label">
                            Jumlah Jersey Terjual
                        </label>

                        <input type="number"
                               id="jumlah_penjualan"
                               name="jumlah_penjualan"
                               class="form-control"
                               min="1"
                               step="1"
                               value="{{ old('jumlah_penjualan') }}"
                               placeholder="Contoh: 5"
                               required>
                    </div>

                    <div class="col-md-3">
                        <button type="submit"
                                class="btn btn-primary w-100">
                            Simpan Transaksi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL AGREGASI BULANAN --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <strong>Agregasi Penjualan Bulanan 2026</strong>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped
                          table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>Bulan</th>
                        <th class="text-end">Total Jersey Terjual</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($agregasiBulanan as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item['bulan'] }}</td>
                            <td class="text-end">
                                {{ number_format(
                                    $item['total'], 0, ',', '.'
                                ) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot class="table-secondary">
                    <tr>
                        <th colspan="2">TOTAL TAHUN 2026</th>
                        <th class="text-end">
                            {{ number_format(
                                $agregasiBulanan->sum('total'),
                                0, ',', '.'
                            ) }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- GRAFIK BATANG CHART.JS --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <strong>Grafik Penjualan Jersey per Bulan</strong>
        </div>

        <div class="card-body">
            <div style="height: 350px; position: relative;">
                <canvas id="grafikPenjualan"></canvas>
            </div>
        </div>
    </div>

    {{-- TABEL DETAIL TRANSAKSI --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <strong>Detail Transaksi Tahun 2026</strong>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover
                          align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>Tanggal Transaksi</th>
                        <th class="text-end">Jumlah Jersey</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($transaksi as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $item->tanggal_transaksi
                                    ->format('d-m-Y') }}
                            </td>
                            <td class="text-end">
                                {{ number_format(
                                    $item->jumlah_penjualan,
                                    0, ',', '.'
                                ) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3"
                                class="text-center text-muted">
                                Belum ada transaksi pada tahun 2026.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    const labelBulan = @json(
        $agregasiBulanan->pluck('bulan')->values()
    );

    const dataPenjualan = @json(
        $agregasiBulanan->pluck('total')->values()
    );

    const ctx = document.getElementById('grafikPenjualan');

    new Chart(ctx, {
        type: 'bar',

        data: {
            labels: labelBulan,

            datasets: [{
                label: 'Jersey Terjual',
                data: dataPenjualan,
                backgroundColor: '#a50044',
                borderColor: '#102b57',
                borderWidth: 1,
                borderRadius: 5
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: true
                },

                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y +
                                ' jersey terjual';
                        }
                    }
                }
            },

            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Jersey Terjual'
                    },
                    ticks: {
                        precision: 0
                    }
                },

                x: {
                    title: {
                        display: true,
                        text: 'Bulan Tahun 2026'
                    }
                }
            }
        }
    });
</script>

</body>
</html>