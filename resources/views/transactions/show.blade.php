@extends('layouts.app')

@section('title', 'Detail Transaksi ' . $transaction->nomor_transaksi)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark m-0">Detail Transaksi {{ $transaction->nomor_transaksi }}</h1>
        <p class="text-muted small m-0 mt-1">Tanggal: {{ formatTanggal($transaction->tanggal, 'l, d F Y H:i') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('transactions.invoice', $transaction) }}" target="_blank" class="btn btn-brand-lime">
            <i class="bi bi-printer"></i> Cetak Faktur / Nota
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="table-card-custom">
            <div class="table-card-header">
                <h5 class="fw-bold text-dark m-0">Daftar Barang Belanja</h5>
                <span class="badge bg-light text-dark border">{{ $transaction->details->count() }} Macam Produk</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle m-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4">Produk</th>
                            <th>Kategori</th>
                            <th>Harga Satuan</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end pe-4">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction->details as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $item->product->nama_barang ?? 'Barang Dihapus' }}</div>
                                    <div class="text-muted small"><code>{{ $item->product->kode_barang ?? '-' }}</code></div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border">
                                        {{ $item->product->kategori->nama ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ formatRupiah($item->harga_satuan) }}</td>
                                <td class="text-center">
                                    <span class="fw-bold">{{ $item->jumlah }}</span> {{ $item->product->satuan ?? 'unit' }}
                                </td>
                                <td class="text-end pe-4 fw-bold text-dark">
                                    {{ formatRupiah($item->subtotal) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Subtotal:</td>
                            <td class="text-end pe-4 fw-bold">{{ formatRupiah($transaction->subtotal) }}</td>
                        </tr>
                        @if($transaction->diskon > 0)
                            <tr>
                                <td colspan="4" class="text-end text-danger fw-bold">Potongan Diskon:</td>
                                <td class="text-end pe-4 text-danger fw-bold">- {{ formatRupiah($transaction->diskon) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="4" class="text-end fw-bold fs-5 text-dark">Total Pembayaran:</td>
                            <td class="text-end pe-4 fw-bold fs-5 text-success">{{ formatRupiah($transaction->total) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-custom">
            <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Informasi Pembeli & Kasir</h5>

            <div class="mb-3">
                <div class="text-muted small">Nama Pelanggan:</div>
                <div class="fw-bold text-dark fs-6">{{ $transaction->nama_pelanggan ?? 'Pelanggan Umum' }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Kasir Bertugas:</div>
                <div class="fw-bold text-dark fs-6 d-flex align-items-center gap-2">
                    <i class="bi bi-person-badge text-success"></i>
                    <span>{{ $transaction->user->name ?? 'Kasir Tanikuy' }}</span>
                </div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Metode Pembayaran:</div>
                <span class="badge bg-light text-dark border px-3 py-2 text-uppercase fs-6">
                    {{ $transaction->metode_pembayaran }}
                </span>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Status Transaksi:</div>
                @if($transaction->status === 'completed')
                    <span class="status-badge-success fs-6">Selesai & Lunas</span>
                @else
                    <span class="status-badge-critical fs-6">Dibatalkan (Stok Rollback)</span>
                @endif
            </div>

            @if($transaction->catatan)
                <div class="mb-3">
                    <div class="text-muted small">Catatan:</div>
                    <div class="p-2 bg-light rounded text-muted small">{{ $transaction->catatan }}</div>
                </div>
            @endif

            @if(auth()->user()->isAdmin() && $transaction->status === 'completed')
                <div class="mt-4 pt-3 border-top">
                    <form action="{{ route('transactions.cancel', $transaction) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini? Seluruh stok barang akan dikembalikan ke inventaris.');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-x-circle me-1"></i> Batalkan Transaksi
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
