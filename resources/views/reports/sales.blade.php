@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark m-0">Laporan Penjualan Bisnis</h1>
        <p class="text-muted small m-0 mt-1">Rekapitulasi transaksi penjualan harian dan omset per periode</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.profit') }}" class="btn btn-outline-success">
            <i class="bi bi-graph-up-arrow"></i> Laporan Laba Kotor
        </a>
        <a href="{{ route('reports.stock') }}" class="btn btn-outline-secondary">
            <i class="bi bi-boxes"></i> Valuasi Inventaris
        </a>
        <button type="button" class="btn btn-brand-lime" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak Laporan
        </button>
    </div>
</div>

<!-- KPI Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card-custom py-3">
            <div class="text-muted small fw-semibold text-uppercase">Total Omset Penjualan</div>
            <div class="h3 fw-bold text-success m-0">{{ formatRupiah($totalRevenue) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-custom py-3">
            <div class="text-muted small fw-semibold text-uppercase">Jumlah Transaksi</div>
            <div class="h3 fw-bold text-dark m-0">{{ $totalTransactions }} Transaksi</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-custom py-3">
            <div class="text-muted small fw-semibold text-uppercase">Rata-rata Transaksi (Basket Size)</div>
            <div class="h3 fw-bold text-dark m-0">{{ formatRupiah($averageTx) }}</div>
        </div>
    </div>
</div>

<div class="table-card-custom">
    <div class="table-card-header">
        <form action="{{ route('reports.sales') }}" method="GET" class="row g-2 w-100 align-items-center">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">Dari:</span>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">Sampai:</span>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-sm btn-brand-forest w-100">
                    <i class="bi bi-filter"></i> Tampilkan Rekap
                </button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle m-0">
            <thead class="table-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4">Tanggal Transaksi</th>
                    <th>Jumlah Transaksi</th>
                    <th>Total Diskon Diberikan</th>
                    <th>Rata-rata Omset</th>
                    <th class="text-end pe-4">Total Omset Harian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dailyReports as $r)
                    <tr>
                        <td class="ps-4 fw-bold text-dark">
                            <i class="bi bi-calendar-event me-2 text-success"></i>
                            {{ formatTanggal($r->tgl, 'l, d F Y') }}
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-3 py-1">
                                {{ $r->total_tx }} Transaksi
                            </span>
                        </td>
                        <td class="text-muted">
                            {{ formatRupiah($r->total_diskon) }}
                        </td>
                        <td class="text-muted">
                            {{ formatRupiah($r->total_tx > 0 ? $r->omset / $r->total_tx : 0) }}
                        </td>
                        <td class="text-end pe-4 fw-bold text-success fs-6">
                            {{ formatRupiah($r->omset) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Tidak ada transaksi dalam periode yang dipilih.</td>
                    </tr>
                @endforelse
            </tbody>
            @if($dailyReports->count() > 0)
                <tfoot class="table-light">
                    <tr>
                        <td class="ps-4 fw-bold fs-6">TOTAL PERIODE:</td>
                        <td class="fw-bold fs-6">{{ $totalTransactions }} Transaksi</td>
                        <td class="text-muted">-</td>
                        <td class="text-muted">-</td>
                        <td class="text-end pe-4 fw-bold fs-5 text-success">{{ formatRupiah($totalRevenue) }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
