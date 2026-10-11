@extends('layouts.app')

@section('title', 'Laporan Laba Kotor')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark m-0">Laporan Laba Kotor (Gross Profit)</h1>
        <p class="text-muted small m-0 mt-1">Analisis keuntungan kotor produk dari selisih omset dan harga pokok penjualan (HPP)</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.sales') }}" class="btn btn-outline-secondary">
            <i class="bi bi-receipt"></i> Laporan Penjualan
        </a>
        <button type="button" class="btn btn-brand-lime" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak Laporan
        </button>
    </div>
</div>

<!-- Profit Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card-custom py-3">
            <div class="text-muted small fw-semibold text-uppercase">Total Penjualan Kotor</div>
            <div class="h3 fw-bold text-dark m-0">{{ formatRupiah($totalSales) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom py-3">
            <div class="text-muted small fw-semibold text-uppercase">Total HPP (Modal Barang Terjual)</div>
            <div class="h3 fw-bold text-secondary m-0">{{ formatRupiah($totalHpp) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom py-3">
            <div class="text-muted small fw-semibold text-uppercase">Laba Kotor Bersih</div>
            <div class="h3 fw-bold text-success m-0">{{ formatRupiah($totalGrossProfit) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom py-3">
            <div class="text-muted small fw-semibold text-uppercase">Margin Laba Kotor Rata-rata</div>
            <div class="h3 fw-bold text-warning m-0">{{ number_format($profitMargin, 1) }}%</div>
        </div>
    </div>
</div>

<div class="table-card-custom">
    <div class="table-card-header">
        <form action="{{ route('reports.profit') }}" method="GET" class="row g-2 w-100 align-items-center">
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
                    <i class="bi bi-filter"></i> Analisis Periode
                </button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle m-0">
            <thead class="table-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4">Nama Produk</th>
                    <th class="text-center">Total Terjual</th>
                    <th>Total Omset</th>
                    <th>Total HPP Modal</th>
                    <th>Laba Kotor</th>
                    <th class="text-end pe-4">Margin (%)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($itemProfits as $item)
                    @php
                        $itemMargin = $item->total_penjualan > 0 ? (($item->laba_kotor / $item->total_penjualan) * 100) : 0;
                    @endphp
                    <tr>
                        <td class="ps-4 fw-bold text-dark">
                            {{ $item->nama_barang }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">
                                {{ $item->total_qty }} {{ $item->satuan }}
                            </span>
                        </td>
                        <td class="fw-semibold text-dark">{{ formatRupiah($item->total_penjualan) }}</td>
                        <td class="text-muted small">{{ formatRupiah($item->total_hpp) }}</td>
                        <td class="fw-bold text-success fs-6">{{ formatRupiah($item->laba_kotor) }}</td>
                        <td class="text-end pe-4">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                {{ number_format($itemMargin, 1) }}%
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Belum ada penjualan di periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
            @if($itemProfits->count() > 0)
                <tfoot class="table-light">
                    <tr>
                        <td class="ps-4 fw-bold fs-6">TOTAL:</td>
                        <td class="text-center fw-bold">{{ $itemProfits->sum('total_qty') }} Unit</td>
                        <td class="fw-bold">{{ formatRupiah($totalSales) }}</td>
                        <td class="fw-bold">{{ formatRupiah($totalHpp) }}</td>
                        <td class="fw-bold text-success fs-5">{{ formatRupiah($totalGrossProfit) }}</td>
                        <td class="text-end pe-4 fw-bold fs-6 text-success">{{ number_format($profitMargin, 1) }}%</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
