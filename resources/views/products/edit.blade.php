@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark m-0">Edit Barang: {{ $product->nama_barang }}</h1>
        <p class="text-muted small m-0 mt-1">Perbarui informasi SKU, harga, dan variabel EOQ</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card-custom">
    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-12">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Informasi Utama</h5>
            </div>

            <div class="col-md-4">
                <label for="kode_barang" class="form-label fw-semibold small">Kode Barang (SKU) <span class="text-danger">*</span></label>
                <input type="text" name="kode_barang" id="kode_barang" class="form-control" value="{{ old('kode_barang', $product->kode_barang) }}" required>
                @error('kode_barang') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-8">
                <label for="nama_barang" class="form-label fw-semibold small">Nama Barang <span class="text-danger">*</span></label>
                <input type="text" name="nama_barang" id="nama_barang" class="form-control" value="{{ old('nama_barang', $product->nama_barang) }}" required>
                @error('nama_barang') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label for="kategori_id" class="form-label fw-semibold small">Kategori <span class="text-danger">*</span></label>
                <select name="kategori_id" id="kategori_id" class="form-select" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('kategori_id', $product->kategori_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="supplier_id" class="form-label fw-semibold small">Supplier Utama</label>
                <select name="supplier_id" id="supplier_id" class="form-select">
                    <option value="">Pilih Supplier</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ old('supplier_id', $product->supplier_id) == $sup->id ? 'selected' : '' }}>
                            {{ $sup->perusahaan ?? $sup->nama }} ({{ $sup->kota }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="satuan" class="form-label fw-semibold small">Satuan <span class="text-danger">*</span></label>
                <select name="satuan" id="satuan" class="form-select" required>
                    @foreach(['pcs', 'kg', 'karung', 'pack', 'liter', 'botol'] as $s)
                        <option value="{{ $s }}" {{ old('satuan', $product->satuan) == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="status" class="form-label fw-semibold small">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>

            <!-- Harga & Stok Minimum -->
            <div class="col-12 mt-4">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Harga & Parameter Stok</h5>
            </div>

            <div class="col-md-4">
                <label for="harga_beli" class="form-label fw-semibold small">Harga Beli / HPP (Rp) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="harga_beli" id="harga_beli" class="form-control" value="{{ old('harga_beli', $product->harga_beli) }}" required min="0">
            </div>

            <div class="col-md-4">
                <label for="harga_jual" class="form-label fw-semibold small">Harga Jual (Rp) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="harga_jual" id="harga_jual" class="form-control" value="{{ old('harga_jual', $product->harga_jual) }}" required min="0">
            </div>

            <div class="col-md-4">
                <label for="stok_minimum" class="form-label fw-semibold small">Stok Minimum (Batas Alert) <span class="text-danger">*</span></label>
                <input type="number" name="stok_minimum" id="stok_minimum" class="form-control" value="{{ old('stok_minimum', $product->stok_minimum) }}" required min="0">
            </div>

            <!-- Variabel EOQ -->
            <div class="col-12 mt-4">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-2">Parameter EOQ</h5>
            </div>

            <div class="col-md-6">
                <label for="biaya_pemesanan" class="form-label fw-semibold small">Biaya Pemesanan per Order (S) — Rp <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="biaya_pemesanan" id="biaya_pemesanan" class="form-control" value="{{ old('biaya_pemesanan', $product->biaya_pemesanan) }}" required min="0">
            </div>

            <div class="col-md-6">
                <label for="biaya_penyimpanan" class="form-label fw-semibold small">Biaya Penyimpanan per Unit per Tahun (H) — Rp <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="biaya_penyimpanan" id="biaya_penyimpanan" class="form-control" value="{{ old('biaya_penyimpanan', $product->biaya_penyimpanan) }}" required min="0">
            </div>

            <div class="col-12">
                <label for="deskripsi" class="form-label fw-semibold small">Deskripsi Produk</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $product->deskripsi) }}</textarea>
            </div>

            <div class="col-12 text-end pt-3 border-top">
                <a href="{{ route('products.index') }}" class="btn btn-light me-2">Batal</a>
                <button type="submit" class="btn btn-brand-lime px-4">
                    <i class="bi bi-check-lg"></i> Perbarui Produk
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
