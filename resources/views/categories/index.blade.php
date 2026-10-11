@extends('layouts.app')

@section('title', 'Kategori Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark m-0">Kategori Produk Pertanian</h1>
        <p class="text-muted small m-0 mt-1">Klasifikasi produk untuk mempermudah inventarisasi dan pelaporan</p>
    </div>
    <button type="button" class="btn btn-brand-lime" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah Kategori</span>
    </button>
</div>

<div class="table-card-custom">
    <div class="table-responsive">
        <table class="table table-hover align-middle m-0">
            <thead class="table-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4">Nama Kategori</th>
                    <th>Warna Label</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Produk</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                    <tr>
                        <td class="ps-4">
                            <span class="badge rounded-pill px-3 py-2 fs-6" style="background-color: {{ $cat->warna }}20; color: {{ $cat->warna }}; border: 1px solid {{ $cat->warna }}40;">
                                <i class="bi bi-tag-fill me-1"></i> {{ $cat->nama }}
                            </span>
                        </td>
                        <td>
                            <code>{{ $cat->warna }}</code>
                            <span class="d-inline-block rounded-circle ms-2" style="width: 14px; height: 14px; background-color: {{ $cat->warna }}; vertical-align: middle;"></span>
                        </td>
                        <td class="text-muted small">
                            {{ $cat->deskripsi ?? '-' }}
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1">
                                {{ $cat->products_count }} Produk
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light border me-1" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $cat->id }}">
                                <i class="bi bi-pencil text-warning"></i>
                            </button>
                            <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editCategoryModal{{ $cat->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content" style="border-radius: var(--radius-xl);">
                                <form action="{{ route('categories.update', $cat) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit Kategori</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Nama Kategori</label>
                                            <input type="text" name="nama" class="form-control" value="{{ $cat->nama }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Warna Badge (Hex)</label>
                                            <input type="color" name="warna" class="form-control form-control-color w-100" value="{{ $cat->warna ?? '#10B981' }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Deskripsi</label>
                                            <textarea name="deskripsi" class="form-control" rows="3">{{ $cat->deskripsi }}</textarea>
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
                        <td colspan="5" class="text-center py-5 text-muted">Belum ada kategori terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
        <div class="p-3 border-top">
            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: var(--radius-xl);">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Kategori Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Pupuk Cair Hayati" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Warna Label</label>
                        <input type="color" name="warna" class="form-control form-control-color w-100" value="#10B981">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Keterangan singkat..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-brand-lime">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
