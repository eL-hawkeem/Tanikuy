@extends('layouts.app')

@section('title', 'Kelola Barang')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark m-0">Katalog Barang Pertanian</h1>
        <p class="text-muted small m-0 mt-1">Daftar inventaris pupuk, benih, beras, pestisida, dan peralatan tani</p>
    </div>
    <div>
        <a href="{{ route('products.create') }}" class="btn btn-brand-lime">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Barang Baru</span>
        </a>
    </div>
</div>

<div class="table-card-custom">
    <!-- Filter Bar -->
    <div class="table-card-header">
        <form action="{{ route('products.index') }}" method="GET" class="row g-2 w-100 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama barang atau kode SKU..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="kategori_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-brand-forest w-100">
                    <i class="bi bi-filter"></i> Filter
                </button>
                @if($search || $categoryId || $status)
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Responsive Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle m-0">
            <thead class="table-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4">Kode & Nama Barang</th>
                    <th>Kategori</th>
                    <th>Supplier</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Stok Saat Ini</th>
                    <th>Status Stok</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $prod)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $prod->nama_barang }}</div>
                            <div class="text-muted small"><code>{{ $prod->kode_barang }}</code> &bull; Satuan: {{ $prod->satuan }}</div>
                        </td>
                        <td>
                            <span class="badge rounded-pill" style="background-color: {{ $prod->kategori->warna ?? '#10B981' }}20; color: {{ $prod->kategori->warna ?? '#10B981' }}; font-weight: 600;">
                                {{ $prod->kategori->nama ?? '-' }}
                            </span>
                        </td>
                        <td class="text-muted small">
                            {{ $prod->supplier->perusahaan ?? ($prod->supplier->nama ?? '-') }}
                        </td>
                        <td class="text-muted small">
                            {{ formatRupiah($prod->harga_beli) }}
                        </td>
                        <td class="fw-bold text-dark">
                            {{ formatRupiah($prod->harga_jual) }}
                        </td>
                        <td>
                            <span class="fw-bold fs-6 {{ $prod->stok_saat_ini <= $prod->stok_minimum ? 'text-danger' : 'text-dark' }}">
                                {{ $prod->stok_saat_ini }} {{ $prod->satuan }}
                            </span>
                            <div class="text-muted" style="font-size: 0.725rem;">Min: {{ $prod->stok_minimum }}</div>
                        </td>
                        <td>
                            @if($prod->stok_saat_ini <= $prod->stok_minimum)
                                <span class="status-badge-critical">
                                    <i class="bi bi-exclamation-circle-fill"></i> Kritis
                                </span>
                            @elseif($prod->stok_saat_ini <= ($prod->stok_minimum * 1.5))
                                <span class="status-badge-warning">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Menipis
                                </span>
                            @else
                                <span class="status-badge-success">
                                    <i class="bi bi-check-circle-fill"></i> Aman
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: var(--radius-md);">
                                    <li>
                                        <a href="{{ route('products.edit', $prod) }}" class="dropdown-item py-2">
                                            <i class="bi bi-pencil me-2 text-warning"></i> Edit Produk
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('eoq.index') }}" class="dropdown-item py-2">
                                            <i class="bi bi-cpu me-2 text-success"></i> Hitung EOQ
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('products.destroy', $prod) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item py-2 text-danger">
                                                <i class="bi bi-trash me-2"></i> Hapus
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                            Tidak ada barang ditemukan. Silakan tambahkan barang baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    @if($products->hasPages())
        <div class="p-3 border-top d-flex justify-content-between align-items-center">
            <span class="small text-muted">
                Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} barang
            </span>
            <div>
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
