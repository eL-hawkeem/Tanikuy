@extends('layouts.app')

@section('title', 'Pemantauan Stok')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark m-0">Pemantauan Stok Otomatis</h1>
        <p class="text-muted small m-0 mt-1">Status ketersediaan stok produk dan tindakan pemesanan</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('stock.movements') }}" class="btn btn-outline-secondary">
            <i class="bi bi-clock-history"></i> Riwayat Pergerakan Stok
        </a>
        <button type="button" class="btn btn-brand-lime" data-bs-toggle="modal" data-bs-target="#restockModal">
            <i class="bi bi-box-arrow-in-down"></i> Terima Barang Masuk
        </button>
    </div>
</div>

<!-- Status Filter Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <a href="{{ route('stock.index', ['status_stok' => 'critical']) }}" class="text-decoration-none">
            <div class="card-custom py-3 {{ $statusStok === 'critical' ? 'border-danger' : '' }}" style="border-left: 5px solid var(--sys-red);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Stok Kritis (&le; Minimum)</div>
                        <div class="h3 fw-bold text-danger m-0">{{ $criticalCount }} SKU</div>
                    </div>
                    <i class="bi bi-exclamation-octagon-fill fs-2 text-danger"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('stock.index', ['status_stok' => 'warning']) }}" class="text-decoration-none">
            <div class="card-custom py-3 {{ $statusStok === 'warning' ? 'border-warning' : '' }}" style="border-left: 5px solid var(--sys-orange);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Peringatan Menipis</div>
                        <div class="h3 fw-bold text-warning m-0">{{ $warningCount }} SKU</div>
                    </div>
                    <i class="bi bi-exclamation-triangle-fill fs-2 text-warning"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('stock.index', ['status_stok' => 'normal']) }}" class="text-decoration-none">
            <div class="card-custom py-3 {{ $statusStok === 'normal' ? 'border-success' : '' }}" style="border-left: 5px solid var(--sys-green);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Kondisi Aman</div>
                        <div class="h3 fw-bold text-success m-0">{{ $normalCount }} SKU</div>
                    </div>
                    <i class="bi bi-check-circle-fill fs-2 text-success"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="table-card-custom">
    <div class="table-card-header">
        <div class="d-flex align-items-center gap-3">
            <h5 class="fw-bold text-dark m-0">Status Inventaris Barang</h5>
            @if($statusStok)
                <a href="{{ route('stock.index') }}" class="badge bg-secondary text-white text-decoration-none">
                    Reset Filter [x]
                </a>
            @endif
        </div>
        <div>
            <form action="{{ route('stock.index') }}" method="GET" class="d-flex gap-2">
                <input type="hidden" name="status_stok" value="{{ $statusStok }}">
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
                    <th class="ps-4">Kode & Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok Sekarang</th>
                    <th>Stok Minimum</th>
                    <th>Status</th>
                    <th>Tindakan Cepat</th>
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
                        <td>
                            <span class="fw-bold fs-6 {{ $prod->stok_saat_ini <= $prod->stok_minimum ? 'text-danger' : 'text-dark' }}">
                                {{ $prod->stok_saat_ini }} {{ $prod->satuan }}
                            </span>
                        </td>
                        <td class="text-muted small">
                            {{ $prod->stok_minimum }} {{ $prod->satuan }}
                        </td>
                        <td>
                            @if($prod->stok_saat_ini <= $prod->stok_minimum)
                                <span class="status-badge-critical">
                                    <i class="bi bi-exclamation-octagon-fill"></i> Kritis (Segera Pesan)
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
                        <td>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#restockItemModal{{ $prod->id }}" title="Terima Barang Masuk">
                                    <i class="bi bi-plus-lg"></i> Restock
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#adjustModal{{ $prod->id }}" title="Stock Opname / Penyesuaian">
                                    <i class="bi bi-sliders"></i> Opname
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Restock Single Modal -->
                    <div class="modal fade" id="restockItemModal{{ $prod->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content" style="border-radius: var(--radius-xl);">
                                <form action="{{ route('stock.restock') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $prod->id }}">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Penerimaan Stok Masuk</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <div class="text-muted small">Produk:</div>
                                            <div class="fw-bold">{{ $prod->nama_barang }} (Sisa: {{ $prod->stok_saat_ini }} {{ $prod->satuan }})</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Jumlah Masuk ({{ $prod->satuan }}) <span class="text-danger">*</span></label>
                                            <input type="number" name="jumlah" class="form-control" min="1" required placeholder="Contoh: 20">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">No. Surat Jalan / Referensi</label>
                                            <input type="text" name="referensi" class="form-control" placeholder="SJ-2026-001">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Keterangan</label>
                                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Pengiriman dari {{ $prod->supplier->nama ?? 'supplier' }}..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-brand-lime">Tambah ke Stok</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Adjustment Modal -->
                    <div class="modal fade" id="adjustModal{{ $prod->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content" style="border-radius: var(--radius-xl);">
                                <form action="{{ route('stock.adjust') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $prod->id }}">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Penyesuaian Stok (Stock Opname)</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <div class="text-muted small">Produk:</div>
                                            <div class="fw-bold">{{ $prod->nama_barang }}</div>
                                            <div class="text-muted small">Stok di sistem saat ini: <strong>{{ $prod->stok_saat_ini }} {{ $prod->satuan }}</strong></div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Stok Fisik Aktual ({{ $prod->satuan }}) <span class="text-danger">*</span></label>
                                            <input type="number" name="stok_aktual" class="form-control" value="{{ $prod->stok_saat_ini }}" min="0" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Alasan Penyesuaian <span class="text-danger">*</span></label>
                                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Barang rusak, selisih hitung fisik saat audit gudang..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-warning fw-bold">Update Stok Aktual</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Tidak ada data produk ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div class="p-3 border-top">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- Global Restock Modal -->
<div class="modal fade" id="restockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: var(--radius-xl);">
            <form action="{{ route('stock.restock') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Terima Barang Masuk dari Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pilih Produk <span class="text-danger">*</span></label>
                        <select name="product_id" class="form-select" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_barang }} (Sisa: {{ $p->stok_saat_ini }} {{ $p->satuan }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Jumlah Masuk <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" class="form-control" min="1" required placeholder="Contoh: 50">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">No. Surat Jalan / Invoice Pembelian</label>
                        <input type="text" name="referensi" class="form-control" placeholder="PO-2026-XXXX">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Catatan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan pengiriman..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-brand-lime">Simpan Barang Masuk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
