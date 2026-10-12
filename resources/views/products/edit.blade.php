@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark m-0">Edit Barang: {{ $product->nama_barang }}</h1>
        <p class="text-muted small m-0 mt-1">Perbarui foto barang, informasi SKU, harga, dan variabel EOQ</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card-custom">
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-12">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-1">Informasi Utama</h5>
                <p class="text-muted small m-0">Kelola foto produk dan data katalog utama</p>
            </div>

            <!-- Upload / Edit Foto Produk -->
            <div class="col-lg-4 col-md-5">
                <label class="form-label fw-semibold small text-dark d-flex align-items-center justify-content-between">
                    <span>Foto Produk</span>
                    <span class="text-muted" style="font-size: 0.75rem;">Maks. 2MB</span>
                </label>
                
                <div id="imageDropzone" class="border border-2 border-dashed rounded-3 p-3 text-center position-relative d-flex flex-column align-items-center justify-content-center {{ $product->gambar_url ? 'bg-white border-success' : 'bg-light' }}" style="min-height: 220px; cursor: pointer; transition: all 0.2s ease;">
                    
                    <!-- Placeholder State (No image existing and none selected) -->
                    <div id="dropzonePlaceholder" class="w-100 py-3 {{ $product->gambar_url ? 'd-none' : '' }}">
                        <div class="rounded-circle bg-white shadow-sm d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                            <i class="bi bi-cloud-arrow-up-fill fs-3 text-success"></i>
                        </div>
                        <div class="fw-semibold text-dark small">Pilih atau Tarik Foto ke Sini</div>
                        <p class="text-muted mb-2" style="font-size: 0.75rem;">Format didukung: JPG, PNG, WEBP</p>
                        <button type="button" class="btn btn-sm btn-outline-success px-3 rounded-pill" onclick="document.getElementById('gambarInput').click();">
                            <i class="bi bi-folder2-open me-1"></i> Telusuri File
                        </button>
                    </div>

                    <!-- Preview State (Existing image or newly selected) -->
                    <div id="dropzonePreview" class="w-100 text-center {{ $product->gambar_url ? '' : 'd-none' }}">
                        <div class="position-relative d-inline-block mb-2">
                            <img id="previewImage" src="{{ $product->gambar_url ?? '#' }}" alt="{{ $product->nama_barang }}" class="rounded-3 shadow-sm border" style="max-height: 140px; max-width: 100%; object-fit: contain; background: #fff;">
                            <button type="button" id="btnRemoveImage" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 translate-middle shadow-sm p-0 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;" title="Hapus foto">
                                <i class="bi bi-x fs-6"></i>
                            </button>
                        </div>
                        <div class="small fw-semibold text-dark text-truncate px-2" id="fileNameDisplay" style="max-width: 220px; margin: 0 auto;">
                            {{ $product->gambar ? basename($product->gambar) : '' }}
                        </div>
                        <div class="text-muted" style="font-size: 0.72rem;" id="fileSizeDisplay">
                            {{ $product->gambar ? 'Foto saat ini tersimpan' : '' }}
                        </div>
                        <button type="button" class="btn btn-link btn-sm text-success text-decoration-none p-0 mt-1" onclick="document.getElementById('gambarInput').click();">
                            <i class="bi bi-arrow-repeat me-1"></i> Ganti Foto
                        </button>
                    </div>

                    <!-- Hidden Input File -->
                    <input type="file" name="gambar" id="gambarInput" class="d-none" accept="image/jpeg,image/png,image/jpg,image/webp">
                    <!-- Hidden input to delete existing image -->
                    <input type="hidden" name="hapus_gambar" id="hapusGambarInput" value="0">
                </div>

                @error('gambar')
                    <div class="text-danger small mt-2 d-flex align-items-center gap-1">
                        <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                    </div>
                @enderror
                <div class="form-text small mt-1 text-muted" style="font-size: 0.75rem;">
                    <i class="bi bi-info-circle me-1"></i> Foto produk akan tampil di katalog kasir dan kartu inventaris.
                </div>
            </div>

            <!-- Identitas Barang -->
            <div class="col-lg-8 col-md-7">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="kode_barang" class="form-label fw-semibold small">Kode Barang (SKU) <span class="text-danger">*</span></label>
                        <input type="text" name="kode_barang" id="kode_barang" class="form-control" value="{{ old('kode_barang', $product->kode_barang) }}" required>
                        @error('kode_barang') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-7">
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
                </div>
            </div>

            <!-- Harga & Stok Minimum -->
            <div class="col-12 mt-4">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Harga & Parameter Stok</h5>
            </div>

            <div class="col-md-4">
                <label for="harga_beli" class="form-label fw-semibold small">Harga Beli / HPP (Rp) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="harga_beli" id="harga_beli" class="form-control" value="{{ old('harga_beli', $product->harga_beli) }}" required min="0">
                @error('harga_beli') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label for="harga_jual" class="form-label fw-semibold small">Harga Jual (Rp) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="harga_jual" id="harga_jual" class="form-control" value="{{ old('harga_jual', $product->harga_jual) }}" required min="0">
                @error('harga_jual') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label for="stok_minimum" class="form-label fw-semibold small">Stok Minimum (Batas Alert) <span class="text-danger">*</span></label>
                <input type="number" name="stok_minimum" id="stok_minimum" class="form-control" value="{{ old('stok_minimum', $product->stok_minimum) }}" required min="0">
                @error('stok_minimum') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropzone = document.getElementById('imageDropzone');
    const input = document.getElementById('gambarInput');
    const hapusGambarInput = document.getElementById('hapusGambarInput');
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
            hapusGambarInput.value = '0';
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
        hapusGambarInput.value = '1';
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
