@extends('layouts.app')

@section('title', 'Riwayat Penjualan')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark m-0">Riwayat Penjualan & Faktur</h1>
        <p class="text-muted small m-0 mt-1">Daftar transaksi penjualan kasir Tanikuy dan pencetakan faktur</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('transactions.create') }}" class="btn btn-brand-lime">
            <i class="bi bi-cart-plus-fill"></i> Transaksi Baru
        </a>
    </div>
</div>

<div class="table-card-custom">
    <!-- Filter Bar -->
    <div class="table-card-header">
        <form action="{{ route('transactions.index') }}" method="GET" class="row g-2 w-100 align-items-center">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari No Invoice / Pembeli..." value="{{ $search }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="metode_pembayaran" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Metode</option>
                    <option value="cash" {{ $paymentMethod === 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                    <option value="transfer" {{ $paymentMethod === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="qris" {{ $paymentMethod === 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" placeholder="Dari Tanggal">
            </div>
            <div class="col-md-2">
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" placeholder="Sampai Tanggal">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-brand-forest w-100" title="Terapkan Filter">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Responsive Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle m-0">
            <thead class="table-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4">No. Invoice & Tanggal</th>
                    <th>Nama Pembeli</th>
                    <th>Metode Bayar</th>
                    <th>Jumlah Item</th>
                    <th>Total Transaksi</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                    <tr>
                        <td class="ps-4">
                            <a href="{{ route('transactions.show', $tx) }}" class="fw-bold text-success text-decoration-none">
                                {{ $tx->nomor_transaksi }}
                            </a>
                            <div class="text-muted small">
                                <i class="bi bi-clock me-1"></i> {{ formatTanggal($tx->tanggal, 'd M Y, H:i') }}
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $tx->nama_pelanggan ?? 'Umum' }}</div>
                            <div class="text-muted small"><i class="bi bi-person-badge me-1"></i>{{ $tx->user->name ?? 'Kasir Tanikuy' }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border text-uppercase">
                                {{ $tx->metode_pembayaran }}
                            </span>
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-light text-dark border">
                                {{ $tx->details->sum('jumlah') }} unit ({{ $tx->details->count() }} jenis)
                            </span>
                        </td>
                        <td class="fw-bold text-dark fs-6">
                            {{ formatRupiah($tx->total) }}
                        </td>
                        <td>
                            @if($tx->status === 'completed')
                                <span class="status-badge-success">Selesai</span>
                            @else
                                <span class="status-badge-critical">Dibatalkan</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('transactions.show', $tx) }}" class="btn btn-sm btn-light border me-1" title="Lihat Rincian">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('transactions.invoice', $tx) }}" target="_blank" class="btn btn-sm btn-outline-success me-1" title="Cetak Faktur">
                                <i class="bi bi-printer"></i>
                            </a>
                            @if(auth()->user()->isAdmin() && $tx->status === 'completed')
                                <form action="{{ route('transactions.cancel', $tx) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini? Stok barang akan dikembalikan otomatis ke inventaris.');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-light border text-danger" title="Batalkan & Kembalikan Stok">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-receipt fs-1 d-block mb-2 text-secondary"></i>
                            Belum ada riwayat transaksi ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transactions->hasPages())
        <div class="p-3 border-top d-flex justify-content-between align-items-center">
            <span class="small text-muted">
                Total Omset Periode Filter: <strong class="text-success">{{ formatRupiah($totalRevenue) }}</strong>
            </span>
            <div>
                {{ $transactions->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
