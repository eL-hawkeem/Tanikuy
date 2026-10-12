@extends('layouts.app')

@section('title', 'Tambah Barang Baru')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark m-0">Tambah Barang Baru</h1>
        <p class="text-muted small m-0 mt-1">Lengkapi data produk, foto barang, parameter harga, dan variabel kalkulasi EOQ</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card-custom">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <!-- Informasi Utama -->
            <div class="col-12">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-1">Informasi Utama Produk</h5>
                <p class="text-muted small m-0">Unggah foto fisik produk dan tentukan identitas katalog</p>
            </div>

            <!-- Upload Foto Produk -->
            <div class="col-lg-4 col-md-5">
                <label class="form-label fw-semibold small text-dark d-flex align-items-center justify-content-between">
                    <span>Foto Produk</span>
                    <span class="text-muted" style="font-size: 0.75rem;">Opsional (Maks. 2MB)</span>
                </label>
                
                <div id="imageDropzone" class="border border-2 border-dashed rounded-3 p-3 text-center position-relative d-flex flex-column align-items-center justify-content-center bg-light" style="min-height: 220px; cursor: pointer; transition: all 0.2s ease;">
                    
                    <!-- Placeholder State (Before Selection) -->
                    <div id="dropzonePlaceholder" class="w-100 py-3">
                        <div class="rounded-circle bg-white shadow-sm d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                            <i class="bi bi-cloud-arrow-up-fill fs-3 text-success"></i>
                        </div>
                        <div class="fw-semibold text-dark small">Pilih atau Tarik Foto ke Sini</div>
                        <p class="text-muted mb-2" style="font-size: 0.75rem;">Format didukung: JPG, PNG, WEBP</p>
                        <button type="button" class="btn btn-sm btn-outline-success px-3 rounded-pill" onclick="document.getElementById('gambarInput').click();">
                            <i class="bi bi-folder2-open me-1"></i> Telusuri File
                        </button>
                    </div>

                    <!-- Preview State (After Selection) -->
                    <div id="dropzonePreview" class="d-none w-100 text-center">
                        <div class="position-relative d-inline-block mb-2">
                            <img id="previewImage" src="#" alt="Preview Foto Produk" class="rounded-3 shadow-sm border" style="max-height: 140px; max-width: 100%; object-fit: contain; background: #fff;">
                            <button type="button" id="btnRemoveImage" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 translate-middle shadow-sm p-0 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;" title="Hapus foto">
                                <i class="bi bi-x fs-6"></i>
                            </button>
                        </div>
                        <div class="small fw-semibold text-dark text-truncate px-2" id="fileNameDisplay" style="max-width: 220px; margin: 0 auto;"></div>
                        <div class="text-muted" style="font-size: 0.72rem;" id="fileSizeDisplay"></div>
                        <button type="button" class="btn btn-link btn-sm text-success text-decoration-none p-0 mt-1" onclick="document.getElementById('gambarInput').click();">
                            <i class="bi bi-arrow-repeat me-1"></i> Ganti Foto
                        </button>
                    </div>

                    <!-- Hidden Input File -->
                    <input type="file" name="gambar" id="gambarInput" class="d-none" accept="image/jpeg,image/png,image/jpg,image/webp">
                </div>

                @error('gambar')
                    <div class="text-danger small mt-2 d-flex align-items-center gap-1">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                    </div>
                @enderror
                <div class="form-text small mt-1 text-muted" style="font-size: 0.75rem;">
                    <i class="bi bi-info-circle me-1"></i> Foto produk akan tampil di katalog kasir dan kartu stok barang.
                </div>
            </div>

            <!-- Identitas Barang -->
            <div class="col-lg-8 col-md-7">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="kode_barang" class="form-label fw-semibold small">Kode Barang (SKU) <span class="text-danger">*</span></label>
                        <input type="text" name="kode_barang" id="kode_barang" class="form-control" value="{{ old('kode_barang', $autoCode) }}" required>
                        @error('kode_barang') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-7">
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
                </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropzone = document.getElementById('imageDropzone');
    const input = document.getElementById('gambarInput');
    const placeholder = document.getElementById('dropzonePlaceholder');
    const preview = document.getElementById('dropzonePreview');
    const previewImage = document.getElementById('previewImage');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const fileSizeDisplay = document.getElementById('fileSizeDisplay');
    const btnRemove = document.getElementById('btnRemoveImage');

    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    function handleFile(file) {
        if (!file) return;

        if (!file.type.match('image.*')) {
            alert('File yang dipilih harus berupa format gambar (JPG, PNG, WEBP).');
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal adalah 2MB. Ukuran file yang dipilih: ' + formatBytes(file.size));
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            previewImage.src = e.target.result;
            fileNameDisplay.textContent = file.name;
            fileSizeDisplay.textContent = formatBytes(file.size);
            placeholder.classList.add('d-none');
            preview.classList.remove('d-none');
            dropzone.classList.add('border-success', 'bg-white');
            dropzone.classList.remove('bg-light');
        };
        reader.readAsDataURL(file);
    }

    dropzone.addEventListener('click', function (e) {
        if (e.target !== btnRemove && !btnRemove.contains(e.target)) {
            input.click();
        }
    });

    input.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            handleFile(this.files[0]);
        }
    });

    btnRemove.addEventListener('click', function (e) {
        e.stopPropagation();
        input.value = '';
        previewImage.src = '#';
        placeholder.classList.remove('d-none');
        preview.classList.add('d-none');
        dropzone.classList.remove('border-success', 'bg-white');
        dropzone.classList.add('bg-light');
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.add('bg-white', 'border-success');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (preview.classList.contains('d-none')) {
                dropzone.classList.remove('bg-white', 'border-success');
            }
        }, false);
    });

    dropzone.addEventListener('drop', function (e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files && files[0]) {
            input.files = files;
            handleFile(files[0]);
        }
    }, false);
});
</script>
@endpush
