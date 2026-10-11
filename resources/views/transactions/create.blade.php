@extends('layouts.app')

@section('title', 'Kasir / Transaksi Penjualan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark m-0">Pencatatan Penjualan (Kasir)</h1>
        <p class="text-muted small m-0 mt-1">Input transaksi penjualan langsung, stok barang akan otomatis berkurang</p>
    </div>
    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-clock-history"></i> Riwayat Penjualan
    </a>
</div>

<form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
    @csrf

    <div class="row g-4">
        <!-- Kolom Kiri: Pemilihan Barang & Keranjang -->
        <div class="col-lg-8">
            <div class="card-custom mb-4">
                <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-cart3 text-success"></i>
                    <span>Pilih Barang Belanja</span>
                </h5>

                <div class="row g-3 align-items-end mb-4 p-3 bg-light rounded-3 border">
                    <div class="col-md-7">
                        <label class="form-label small fw-semibold">Pilih Produk Pertanian</label>
                        <select id="productSelect" class="form-select">
                            <option value="">-- Cari / Pilih Produk --</option>
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}"
                                    data-nama="{{ $prod->nama_barang }}"
                                    data-kode="{{ $prod->kode_barang }}"
                                    data-harga="{{ $prod->harga_jual }}"
                                    data-stok="{{ $prod->stok_saat_ini }}"
                                    data-satuan="{{ $prod->satuan }}">
                                    {{ $prod->nama_barang }} (Sisa: {{ $prod->stok_saat_ini }} {{ $prod->satuan }}) — {{ formatRupiah($prod->harga_jual) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Jumlah (Qty)</label>
                        <input type="number" id="productQty" class="form-control" value="1" min="1">
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="btnAddItem" class="btn btn-brand-lime w-100">
                            <i class="bi bi-plus-lg"></i> Tambah
                        </button>
                    </div>
                </div>

                <!-- Tabel Item Transaksi -->
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="cartTable">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th>Barang</th>
                                <th style="width: 150px;">Harga Satuan</th>
                                <th style="width: 120px;">Jumlah</th>
                                <th style="width: 160px;">Subtotal</th>
                                <th style="width: 50px;"></th>
                            </tr>
                        </thead>
                        <tbody id="cartTableBody">
                            <tr id="emptyRow">
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-cart-x fs-2 d-block mb-1 text-secondary"></i>
                                    Belum ada barang di dalam daftar belanja.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Rincian Pembayaran & Checkout -->
        <div class="col-lg-4">
            <div class="card-custom position-sticky" style="top: 100px;">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Rincian Pembayaran</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nama Pembeli / Pelanggan</label>
                    <input type="text" name="nama_pelanggan" class="form-control" placeholder="Contoh: Pak Slamet / Kelompok Tani" value="{{ old('nama_pelanggan') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Metode Pembayaran <span class="text-danger">*</span></label>
                    <div class="d-flex gap-2">
                        <input type="radio" class="btn-check" name="metode_pembayaran" id="pay_cash" value="cash" checked>
                        <label class="btn btn-outline-success flex-fill fw-semibold py-2" for="pay_cash">
                            <i class="bi bi-cash me-1"></i> Tunai
                        </label>

                        <input type="radio" class="btn-check" name="metode_pembayaran" id="pay_transfer" value="transfer">
                        <label class="btn btn-outline-success flex-fill fw-semibold py-2" for="pay_transfer">
                            <i class="bi bi-bank me-1"></i> Transfer
                        </label>

                        <input type="radio" class="btn-check" name="metode_pembayaran" id="pay_qris" value="qris">
                        <label class="btn btn-outline-success flex-fill fw-semibold py-2" for="pay_qris">
                            <i class="bi bi-qr-code me-1"></i> QRIS
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Diskon Penjualan (Rp)</label>
                    <input type="number" name="diskon" id="diskonInput" class="form-control" value="0" min="0" step="100">
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Catatan Transaksi</label>
                    <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan opsional..."></textarea>
                </div>

                <!-- Ringkasan Nominal -->
                <div class="p-3 rounded-3 mb-4" style="background-color: var(--bs-body-bg);">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Subtotal:</span>
                        <span class="fw-semibold text-dark" id="displaySubtotal">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Diskon:</span>
                        <span class="fw-semibold text-danger" id="displayDiskon">- Rp 0</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark">Total Bayar:</span>
                        <span class="h4 fw-bold text-success m-0" id="displayTotal">Rp 0</span>
                    </div>
                </div>

                <button type="submit" id="btnSubmitTx" class="btn btn-brand-lime w-100 py-3 fw-bold fs-6" disabled>
                    <i class="bi bi-check2-circle me-1"></i> Simpan Transaksi & Cetak
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let cart = [];

        const productSelect = document.getElementById('productSelect');
        const productQty = document.getElementById('productQty');
        const btnAddItem = document.getElementById('btnAddItem');
        const cartTableBody = document.getElementById('cartTableBody');
        const emptyRow = document.getElementById('emptyRow');
        const diskonInput = document.getElementById('diskonInput');
        const displaySubtotal = document.getElementById('displaySubtotal');
        const displayDiskon = document.getElementById('displayDiskon');
        const displayTotal = document.getElementById('displayTotal');
        const btnSubmitTx = document.getElementById('btnSubmitTx');

        function formatIDR(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        }

        function renderCart() {
            if (cart.length === 0) {
                cartTableBody.innerHTML = `
                    <tr id="emptyRow">
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="bi bi-cart-x fs-2 d-block mb-1 text-secondary"></i>
                            Belum ada barang di dalam daftar belanja.
                        </td>
                    </tr>
                `;
                btnSubmitTx.disabled = true;
                displaySubtotal.innerText = formatIDR(0);
                displayDiskon.innerText = '- ' + formatIDR(0);
                displayTotal.innerText = formatIDR(0);
                return;
            }

            btnSubmitTx.disabled = false;
            let rowsHtml = '';
            let subtotal = 0;

            cart.forEach((item, index) => {
                const lineTotal = item.harga * item.qty;
                subtotal += lineTotal;

                rowsHtml += `
                    <tr>
                        <td>
                            <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                            <input type="hidden" name="items[${index}][jumlah]" value="${item.qty}">
                            <div class="fw-bold text-dark">${item.nama}</div>
                            <div class="text-muted small"><code>${item.kode}</code> &bull; Stok: ${item.stok} ${item.satuan}</div>
                        </td>
                        <td>${formatIDR(item.harga)}</td>
                        <td>
                            <input type="number" class="form-control form-control-sm item-qty-input" data-index="${index}" value="${item.qty}" min="1" max="${item.stok}">
                        </td>
                        <td class="fw-bold text-dark">${formatIDR(lineTotal)}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item" data-index="${index}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            cartTableBody.innerHTML = rowsHtml;

            const diskon = parseFloat(diskonInput.value) || 0;
            const total = Math.max(0, subtotal - diskon);

            displaySubtotal.innerText = formatIDR(subtotal);
            displayDiskon.innerText = '- ' + formatIDR(diskon);
            displayTotal.innerText = formatIDR(total);

            // Bind delete button
            document.querySelectorAll('.btn-remove-item').forEach(btn => {
                btn.addEventListener('click', function () {
                    const idx = parseInt(this.getAttribute('data-index'));
                    cart.splice(idx, 1);
                    renderCart();
                });
            });

            // Bind qty change
            document.querySelectorAll('.item-qty-input').forEach(input => {
                input.addEventListener('change', function () {
                    const idx = parseInt(this.getAttribute('data-index'));
                    let val = parseInt(this.value) || 1;
                    if (val > cart[idx].stok) {
                        alert('Jumlah melebihi stok yang tersedia (' + cart[idx].stok + ')');
                        val = cart[idx].stok;
                        this.value = val;
                    }
                    if (val < 1) val = 1;
                    cart[idx].qty = val;
                    renderCart();
                });
            });
        }

        btnAddItem.addEventListener('click', function () {
            const selectedOpt = productSelect.options[productSelect.selectedIndex];
            if (!selectedOpt || !selectedOpt.value) {
                alert('Silakan pilih produk terlebih dahulu.');
                return;
            }

            const id = parseInt(selectedOpt.value);
            const nama = selectedOpt.getAttribute('data-nama');
            const kode = selectedOpt.getAttribute('data-kode');
            const harga = parseFloat(selectedOpt.getAttribute('data-harga'));
            const stok = parseInt(selectedOpt.getAttribute('data-stok'));
            const satuan = selectedOpt.getAttribute('data-satuan');
            let qty = parseInt(productQty.value) || 1;

            if (qty <= 0) qty = 1;

            if (qty > stok) {
                alert('Stok barang hanya tersedia: ' + stok + ' ' + satuan);
                return;
            }

            // Check if already in cart
            const existing = cart.find(i => i.id === id);
            if (existing) {
                if (existing.qty + qty > stok) {
                    alert('Total belanja untuk barang ini melebihi stok yang tersedia (' + stok + ').');
                    return;
                }
                existing.qty += qty;
            } else {
                cart.push({ id, nama, kode, harga, stok, satuan, qty });
            }

            productSelect.selectedIndex = 0;
            productQty.value = 1;
            renderCart();
        });

        diskonInput.addEventListener('input', function () {
            renderCart();
        });
    });
</script>
@endpush
