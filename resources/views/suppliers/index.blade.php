@extends('layouts.app')

@section('title', 'Kelola Supplier')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark m-0">Kelola Supplier / Pemasok</h1>
        <p class="text-muted small m-0 mt-1">Daftar distributor pupuk, benih, dan produsen alat pertanian</p>
    </div>
    <button type="button" class="btn btn-brand-lime" data-bs-toggle="modal" data-bs-target="#createSupplierModal">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah Supplier</span>
    </button>
</div>

<div class="table-card-custom">
    <div class="table-card-header">
        <form action="{{ route('suppliers.index') }}" method="GET" class="w-100" style="max-width: 400px;">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama, perusahaan, atau kota..." value="{{ $search }}">
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle m-0">
            <thead class="table-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4">Kode & Perusahaan</th>
                    <th>Kontak Person</th>
                    <th>Telepon / Email</th>
                    <th>Kota & Alamat</th>
                    <th>Jumlah Produk</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $sup)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $sup->perusahaan ?? $sup->nama }}</div>
                            <div class="text-muted small"><code>{{ $sup->kode_supplier }}</code></div>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $sup->nama }}</div>
                        </td>
                        <td>
                            <div><i class="bi bi-telephone text-success me-1"></i> {{ $sup->telepon }}</div>
                            @if($sup->email)
                                <div class="text-muted small"><i class="bi bi-envelope me-1"></i> {{ $sup->email }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $sup->kota }}</div>
                            <div class="text-muted small text-truncate" style="max-width: 200px;">{{ $sup->alamat }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1">
                                {{ $sup->products_count }} Produk
                            </span>
                        </td>
                        <td>
                            @if($sup->status === 'active')
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Aktif</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light border me-1" data-bs-toggle="modal" data-bs-target="#editSupplierModal{{ $sup->id }}">
                                <i class="bi bi-pencil text-warning"></i>
                            </button>
                            <form action="{{ route('suppliers.destroy', $sup) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus supplier ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Supplier Modal -->
                    <div class="modal fade" id="editSupplierModal{{ $sup->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" style="border-radius: var(--radius-xl);">
                                <form action="{{ route('suppliers.update', $sup) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit Supplier {{ $sup->kode_supplier }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Nama Perusahaan / Toko</label>
                                                <input type="text" name="perusahaan" class="form-control" value="{{ $sup->perusahaan }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Nama Kontak Person <span class="text-danger">*</span></label>
                                                <input type="text" name="nama" class="form-control" value="{{ $sup->nama }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">No. Telepon / WhatsApp <span class="text-danger">*</span></label>
                                                <input type="text" name="telepon" class="form-control" value="{{ $sup->telepon }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Email</label>
                                                <input type="email" name="email" class="form-control" value="{{ $sup->email }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Kota <span class="text-danger">*</span></label>
                                                <input type="text" name="kota" class="form-control" value="{{ $sup->kota }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="active" {{ $sup->status === 'active' ? 'selected' : '' }}>Aktif</option>
                                                    <option value="inactive" {{ $sup->status === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                                                <textarea name="alamat" class="form-control" rows="2" required>{{ $sup->alamat }}</textarea>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-semibold">Catatan</label>
                                                <textarea name="catatan" class="form-control" rows="2">{{ $sup->catatan }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-brand-lime">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Belum ada supplier terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($suppliers->hasPages())
        <div class="p-3 border-top">
            {{ $suppliers->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- Modal Tambah Supplier -->
<div class="modal fade" id="createSupplierModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: var(--radius-xl);">
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Supplier Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Perusahaan / Distributor</label>
                            <input type="text" name="perusahaan" class="form-control" placeholder="Contoh: PT Pupuk Petro Tani">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Kontak Person <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" placeholder="Contoh: Bpk. H. Rahmat" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">No. Telepon / WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" name="telepon" class="form-control" placeholder="081234567890" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="supplier@email.com">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold">Kota Asal <span class="text-danger">*</span></label>
                            <input type="text" name="kota" class="form-control" placeholder="Contoh: Surabaya" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="alamat" class="form-control" rows="2" placeholder="Jl. Raya Industri..." required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Catatan</label>
                            <textarea name="catatan" class="form-control" rows="2" placeholder="Ketentuan pembayaran, tempo kredit, dll..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-brand-lime">Simpan Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
