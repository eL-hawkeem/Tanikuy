@extends('layouts.app')

@section('title', 'Laporan Valuasi Stok')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark m-0">Laporan Valuasi Inventaris & Aset</h1>
        <p class="text-muted small m-0 mt-1">Total nilai modal tersimpan di gudang dan estimasi potensi pendapatan</p>
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

<!-- Valuation Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card-custom py-3">
            <div class="text-muted small fw-semibold text-uppercase">Total Fisik Unit Stok</div>
            <div class="h3 fw-bold text-dark m-0">{{ number_format($totalStockUnits, 0, ',', '.') }} Unit</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom py-3">
            <div class="text-muted small fw-semibold text-uppercase">Nilai Modal / HPP Persediaan</div>
            <div class="h3 fw-bold text-primary m-0">{{ formatRupiah($totalAssetCost) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom py-3">
            <div class="text-muted small fw-semibold text-uppercase">Nilai Jual Persediaan</div>
            <div class="h3 fw-bold text-success m-0">{{ formatRupiah($totalAssetPotential) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom py-3">
            <div class="text-muted small fw-semibold text-uppercase">Potensi Margin Tertahan</div>
            <div class="h3 fw-bold text-warning m-0">{{ formatRupiah($potentialProfit) }}</div>
        </div>
    </div>
</div>

<div class="table-card-custom">
    <div class="table-card-header">
        <h5 class="fw-bold text-dark m-0">Rincian Valuasi Stok per SKU</h5>
        <span class="badge bg-light text-dark border">{{ $products->count() }} Produk</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle m-0">
            <thead class="table-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4">Kode & Nama Barang</th>
                    <th>Kategori</th>
                    <th>Sisa Stok</th>
                    <th>Harga Beli (HPP)</th>
                    <th>Harga Jual</th>
                    <th>Total Nilai Modal</th>
                    <th class="text-end pe-4">Total Nilai Jual</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $prod)
                    @php
                        $assetCost = $prod->stok_saat_ini * (float)$prod->harga_beli;
                        $assetPotential = $prod->stok_saat_ini * (float)$prod->harga_jual;
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $prod->nama_barang }}</div>
                            <div class="text-muted small"><code>{{ $prod->kode_barang }}</code></div>
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-light text-dark border">
                                {{ $prod->kategori->nama ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $prod->stok_saat_ini }}</span> {{ $prod->satuan }}
                        </td>
                        <td class="text-muted small">{{ formatRupiah($prod->harga_beli) }}</td>
                        <td class="text-muted small">{{ formatRupiah($prod->harga_jual) }}</td>
                        <td class="fw-bold text-primary">{{ formatRupiah($assetCost) }}</td>
                        <td class="text-end pe-4 fw-bold text-success">{{ formatRupiah($assetPotential) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
