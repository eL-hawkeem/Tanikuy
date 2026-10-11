@extends('layouts.app')

@section('title', 'Algoritma EOQ & Data Kebutuhan')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark m-0 d-flex align-items-center gap-2">
            <span>Algoritma EOQ & Data Kebutuhan Barang</span>
            <span class="badge bg-success-subtle text-success fs-6 border border-success-subtle">Economic Order Quantity</span>
        </h1>
        <p class="text-muted small m-0 mt-1">Optimasi kuantitas pemesanan barang pertanian untuk meminimalkan total biaya inventaris</p>
    </div>
    <div class="d-flex gap-2">
        <form action="{{ route('eoq.calculate.all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-brand-lime">
                <i class="bi bi-arrow-clockwise"></i> Hitung Ulang Semua EOQ
            </button>
        </form>
    </div>
</div>

<!-- Formula & Insight Banner -->
<div class="row g-4 mb-4">
    <!-- Formula Card -->
    <div class="col-lg-7">
        <div class="card-custom h-100" style="background-color: var(--brand-forest-dark); color: #FFFFFF; border-color: var(--border-dark-green);">
            <div class="d-flex align-items-center gap-2 mb-3 text-warning">
                <i class="bi bi-cpu-fill fs-4"></i>
                <h5 class="fw-bold m-0 text-white">Prinsip Matematis Algoritma EOQ</h5>
            </div>
            <div class="p-3 rounded-3 mb-3" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
                <div class="h4 text-center my-2" style="font-family: monospace; letter-spacing: 2px;">
                    <span style="color: var(--brand-lime); font-weight: 800;">EOQ (Q*) = &radic;( (2 &times; D &times; S) / H )</span>
                </div>
            </div>
            <div class="row g-2 small text-white-50">
                <div class="col-md-4">
                    <strong class="text-white">D (Demand Tahunan):</strong> Total konsumsi/penjualan barang selama 1 tahun.
                </div>
                <div class="col-md-4">
                    <strong class="text-white">S (Ordering Cost):</strong> Biaya pemesanan & angkutan setiap kali order.
                </div>
                <div class="col-md-4">
                    <strong class="text-white">H (Holding Cost):</strong> Biaya simpan per unit di gudang per tahun.
                </div>
            </div>
        </div>
    </div>

    <!-- Reorder Summary Metrics -->
    <div class="col-lg-5">
        <div class="card-custom h-100">
            <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Ringkasan Kebutuhan Belanja</h5>
            <div class="mb-3">
                <div class="text-muted small">Barang yang Perlu Reorder Segera:</div>
                <div class="h2 fw-bold text-danger m-0">{{ $reorderNeededProducts->count() }} Produk</div>
                <div class="small text-muted">Stok saat ini telah menyentuh batas minimum.</div>
            </div>
            <div class="mb-4">
                <div class="text-muted small">Estimasi Modal Pemesanan Optimal:</div>
                <div class="h3 fw-bold text-success m-0">{{ formatRupiah($totalEstimatedReorderCost) }}</div>
                <div class="small text-muted">Kalkulasi: &sum; (Kuantitas EOQ &times; Harga Beli).</div>
            </div>
            @if($reorderNeededProducts->count() > 0)
                <a href="{{ route('eoq.index', ['only_reorder' => 1]) }}" class="btn btn-outline-danger btn-sm w-100 fw-semibold">
                    <i class="bi bi-funnel"></i> Tampilkan Hanya Barang Kritis
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Main EOQ Table Card -->
<div class="table-card-custom">
    <div class="table-card-header">
        <div class="d-flex align-items-center gap-3">
            <h5 class="fw-bold text-dark m-0">Tabel Rekomendasi Pemesanan Optimal (EOQ)</h5>
            @if($onlyReorder)
                <a href="{{ route('eoq.index') }}" class="badge bg-secondary text-white text-decoration-none">
                    Tampilkan Semua Produk [x]
                </a>
            @endif
        </div>
        <div>
            <form action="{{ route('eoq.index') }}" method="GET" class="d-flex gap-2">
                <input type="hidden" name="only_reorder" value="{{ $onlyReorder ? 1 : 0 }}">
                <select name="kategori_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $kategoriId == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nama }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle m-0">
            <thead class="table-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4">Produk & SKU</th>
                    <th>Stok Sekarang / Min</th>
                    <th>Demand Tahunan (D)</th>
                    <th>Biaya Pesan (S)</th>
                    <th>Biaya Simpan (H)</th>
                    <th class="table-success text-success">Jumlah Order EOQ (Q*)</th>
                    <th>Frekuensi Order</th>
                    <th>Estimasi Biaya Order</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $prod)
                    <tr class="{{ $prod->stok_saat_ini <= $prod->stok_minimum ? 'table-danger-subtle' : '' }}">
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $prod->nama_barang }}</div>
                            <div class="text-muted small">
                                <code>{{ $prod->kode_barang }}</code> &bull; {{ $prod->kategori->nama ?? '-' }}
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold {{ $prod->stok_saat_ini <= $prod->stok_minimum ? 'text-danger' : 'text-dark' }}">
                                {{ $prod->stok_saat_ini }} {{ $prod->satuan }}
                            </div>
                            <div class="text-muted" style="font-size: 0.75rem;">Min: {{ $prod->stok_minimum }} {{ $prod->satuan }}</div>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $prod->eoq->annual_demand }}</span> {{ $prod->satuan }}/th
                        </td>
                        <td class="text-muted small">{{ formatRupiah($prod->eoq->ordering_cost) }}</td>
                        <td class="text-muted small">{{ formatRupiah($prod->eoq->holding_cost) }}</td>
                        <td class="table-success">
                            <span class="badge bg-success fs-6 px-3 py-2">
                                {{ $prod->eoq->eoq_qty }} {{ $prod->satuan }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $prod->eoq->order_frequency }}x</span> / tahun
                        </td>
                        <td class="fw-bold text-dark">
                            {{ formatRupiah($prod->estimated_reorder_cost) }}
                        </td>
                        <td class="text-end pe-4">
                            <form action="{{ route('eoq.calculate.single', $prod) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-light border" title="Hitung Ulang">
                                    <i class="bi bi-arrow-repeat text-success"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">Tidak ada data produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
