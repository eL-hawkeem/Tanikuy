@extends('layouts.app')

@section('title', 'Riwayat Pergerakan Stok')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark m-0">Audit Log Pergerakan Stok</h1>
        <p class="text-muted small m-0 mt-1">Catatan riwayat barang masuk, keluar (penjualan), dan penyesuaian opname</p>
    </div>
    <a href="{{ route('stock.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Pemantauan
    </a>
</div>

<div class="table-card-custom">
    <!-- Filter bar -->
    <div class="table-card-header">
        <form action="{{ route('stock.movements') }}" method="GET" class="row g-2 w-100 align-items-center">
            <div class="col-md-5">
                <select name="product_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Produk</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ $productId == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_barang }} ({{ $p->kode_barang }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="tipe" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Jenis Pergerakan</option>
                    <option value="in" {{ $tipe === 'in' ? 'selected' : '' }}>Barang Masuk (IN)</option>
                    <option value="out" {{ $tipe === 'out' ? 'selected' : '' }}>Barang Keluar (OUT)</option>
                    <option value="adjustment" {{ $tipe === 'adjustment' ? 'selected' : '' }}>Penyesuaian (ADJUSTMENT)</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-brand-forest w-100">
                    <i class="bi bi-funnel"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle m-0">
            <thead class="table-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4">Waktu & Tanggal</th>
                    <th>Nama Barang</th>
                    <th>Jenis Pergerakan</th>
                    <th>Jumlah</th>
                    <th>Stok Sebelum</th>
                    <th>Stok Sesudah</th>
                    <th>Referensi & Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $m)
                    <tr>
                        <td class="ps-4 text-muted small">
                            <i class="bi bi-clock me-1"></i> {{ formatTanggal($m->created_at, 'd M Y, H:i') }}
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $m->product->nama_barang ?? 'Produk Dihapus' }}</div>
                            <div class="text-muted small"><code>{{ $m->product->kode_barang ?? '-' }}</code></div>
                        </td>
                        <td>
                            @if($m->tipe === 'in')
                                <span class="status-badge-success">
                                    <i class="bi bi-arrow-down-left"></i> Masuk (IN)
                                </span>
                            @elseif($m->tipe === 'out')
                                <span class="status-badge-critical">
                                    <i class="bi bi-arrow-up-right"></i> Keluar (OUT)
                                </span>
                            @else
                                <span class="status-badge-warning">
                                    <i class="bi bi-sliders"></i> Opname
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold {{ $m->tipe === 'in' ? 'text-success' : ($m->tipe === 'out' ? 'text-danger' : 'text-warning') }}">
                                {{ $m->tipe === 'in' ? '+' : ($m->tipe === 'out' ? '-' : '±') }}{{ $m->jumlah }} {{ $m->product->satuan ?? '' }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $m->stok_sebelum }}</td>
                        <td class="fw-bold text-dark">{{ $m->stok_sesudah }}</td>
                        <td>
                            <div class="fw-semibold text-dark small">{{ $m->referensi ?? '-' }}</div>
                            <div class="text-muted small">{{ $m->keterangan ?? '-' }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Belum ada riwayat pergerakan stok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($movements->hasPages())
        <div class="p-3 border-top">
            {{ $movements->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
