@extends('layouts.app')

@section('title', 'Tambah Barang Baru')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark m-0">Tambah Barang Baru</h1>
        <p class="text-muted small m-0 mt-1">Lengkapi data produk, parameter harga, dan variabel kalkulasi EOQ</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card-custom">
    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            <!-- Informasi Utama -->
            <div class="col-12">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Informasi Utama Produk</h5>
            </div>

            <div class="col-md-4">
                <label for="kode_barang" class="form-label fw-semibold small">Kode Barang (SKU) <span class="text-danger">*</span></label>
                <input type="text" name="kode_barang" id="kode_barang" class="form-control" value="{{ old('kode_barang', $autoCode) }}" required>
                @error('kode_barang') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-8">
                <label for="nama_barang" class="form-label fw-semibold small">Nama Barang <span class="text-danger">*</span></label>
                <input type="text" name="nama_barang" id="nama_barang" class="form-control" value="{{ old('nama_barang') }}" placeholder="Contoh: Pupuk NPK Mutiara 16-16-16 (1kg)" required>
                @error('nama_barang') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label for="kategori_id" class="form-label fw-semibold small">Kategori <span class="text-danger">*</span></label>
                <select name="kategori_id" id="kategori_id" class="form-select" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('kategori_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nama }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label for="supplier_id" class="form-label fw-semibold small">Supplier Utama</label>
                <select name="supplier_id" id="supplier_id" class="form-select">
                    <option value="">Pilih Supplier</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                            {{ $sup->perusahaan ?? $sup->nama }} ({{ $sup->kota }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="satuan" class="form-label fw-semibold small">Satuan Ukuran <span class="text-danger">*</span></label>
                <select name="satuan" id="satuan" class="form-select" required>
                    <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>pcs (Buah/Alat)</option>
                    <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>kg (Kilogram)</option>
                    <option value="karung" {{ old('satuan') == 'karung' ? 'selected' : '' }}>karung (Sak)</option>
                    <option value="pack" {{ old('satuan') == 'pack' ? 'selected' : '' }}>pack (Bungkus)</option>
                    <option value="liter" {{ old('satuan') == 'liter' ? 'selected' : '' }}>liter (Cair)</option>
                    <option value="botol" {{ old('satuan') == 'botol' ? 'selected' : '' }}>botol</option>
                </select>
            </div>

            <!-- Harga & Stok -->
            <div class="col-12 mt-4">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Harga & Stok Awal</h5>
            </div>

            <div class="col-md-3">
                <label for="harga_beli" class="form-label fw-semibold small">Harga Beli / HPP (Rp) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="harga_beli" id="harga_beli" class="form-control" value="{{ old('harga_beli', 0) }}" required min="0">
                @error('harga_beli') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label for="harga_jual" class="form-label fw-semibold small">Harga Jual (Rp) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="harga_jual" id="harga_jual" class="form-control" value="{{ old('harga_jual', 0) }}" required min="0">
                @error('harga_jual') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label for="stok_saat_ini" class="form-label fw-semibold small">Stok Awal <span class="text-danger">*</span></label>
                <input type="number" name="stok_saat_ini" id="stok_saat_ini" class="form-control" value="{{ old('stok_saat_ini', 0) }}" required min="0">
                @error('stok_saat_ini') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-3">
                <label for="stok_minimum" class="form-label fw-semibold small">Stok Minimum (Batas Alert) <span class="text-danger">*</span></label>
                <input type="number" name="stok_minimum" id="stok_minimum" class="form-control" value="{{ old('stok_minimum', 10) }}" required min="0">
                @error('stok_minimum') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <!-- Variabel EOQ -->
            <div class="col-12 mt-4">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-2 d-flex align-items-center gap-2">
                    <i class="bi bi-cpu-fill text-success"></i>
                    <span>Parameter Algoritma EOQ (Economic Order Quantity)</span>
                </h5>
                <p class="text-muted small">Variabel ini digunakan sistem untuk menghitung kuantitas pemesanan paling ekonomis</p>
            </div>

            <div class="col-md-6">
                <label for="biaya_pemesanan" class="form-label fw-semibold small">Biaya Pemesanan per Order (S) — Rp <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="biaya_pemesanan" id="biaya_pemesanan" class="form-control" value="{{ old('biaya_pemesanan', 50000) }}" required min="0">
                <div class="form-text small">Biaya kurir, transportasi angkut, atau administrasi setiap kali order.</div>
            </div>

            <div class="col-md-6">
                <label for="biaya_penyimpanan" class="form-label fw-semibold small">Biaya Penyimpanan per Unit per Tahun (H) — Rp <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="biaya_penyimpanan" id="biaya_penyimpanan" class="form-control" value="{{ old('biaya_penyimpanan', 3500) }}" required min="0">
                <div class="form-text small">Biaya sewa gudang, risiko susut, dan perawatan per unit per tahun.</div>
            </div>

            <div class="col-12">
                <label for="deskripsi" class="form-label fw-semibold small">Deskripsi Produk</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="Keterangan spesifikasi bibit, dosis pupuk, atau kelengkapan alat...">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="col-12 text-end pt-3 border-top">
                <a href="{{ route('products.index') }}" class="btn btn-light me-2">Batal</a>
                <button type="submit" class="btn btn-brand-lime px-4">
                    <i class="bi bi-check-lg"></i> Simpan Produk
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
