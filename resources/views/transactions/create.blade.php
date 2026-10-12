@extends('layouts.app')

@section('title', 'Kasir / POS Penjualan')

@section('content')
<style>
    .pos-product-card {
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        cursor: pointer;
        user-select: none;
        border-radius: var(--radius-lg, 12px);
        overflow: hidden;
        border: 1px solid var(--border-light, #e2e8f0);
        background: #ffffff;
    }
    .pos-product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px -5px rgba(20, 54, 40, 0.1);
        border-color: var(--brand-forest, #143628);
    }
    .pos-product-card:active {
        transform: scale(0.98);
    }
    .pos-product-card.in-cart {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 1px #10b981;
    }
    .pos-product-img {
        width: 100%;
        height: 110px;
        object-fit: cover;
        background-color: #f8fafc;
    }
    .category-filter-bar {
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .category-filter-bar::-webkit-scrollbar {
        display: none;
    }
    .category-pill {
        border-radius: 50rem;
        padding: 0.38rem 0.9rem;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .category-pill:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .category-pill.active {
        background: var(--brand-forest, #143628) !important;
        color: var(--brand-lime, #B4F105) !important;
        border-color: var(--brand-forest, #143628) !important;
    }
    .cart-items-scroll {
        max-height: 340px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    .cart-items-scroll::-webkit-scrollbar {
        width: 4px;
    }
    .cart-items-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .btn-stepper {
        width: 28px;
        height: 28px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-weight: bold;
    }
    .product-grid-container {
        max-height: calc(100vh - 220px);
        overflow-y: auto;
        padding-right: 4px;
        scrollbar-width: thin;
    }
</style>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h1 class="h3 fw-bold text-dark m-0">Pencatatan Penjualan (Kasir / POS)</h1>
        <p class="text-muted small m-0 mt-1">Klik langsung pada kartu barang di bawah untuk memasukkan ke keranjang belanja</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-clock-history"></i> Riwayat Penjualan
        </a>
    </div>
</div>

<form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
    @csrf

    <div class="row g-4">
        <!-- Kolom Kiri: Katalog Barang POS (Grid Interaktif) -->
        <div class="col-lg-7 col-xl-8">
            <div class="card-custom mb-4 p-3 p-md-4">
                
                <!-- Toolbar Pencarian & Kategori -->
                <div class="row g-2 align-items-center mb-3">
                    <div class="col-md-7">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="posSearchInput" class="form-control border-start-0 ps-0" placeholder="Cari nama barang atau kode SKU..." autocomplete="off">
                            <button class="btn btn-outline-secondary border-start-0 d-none" type="button" id="btnClearSearch">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-5 text-md-end text-muted small">
                        <span>Menampilkan <strong id="visibleProductCount">{{ count($products) }}</strong> produk</span>
                    </div>
                </div>

                <!-- Category Filter Pills -->
                <div class="category-filter-bar d-flex gap-2 pb-2 mb-3">
                    <button type="button" class="category-pill active" data-category="all">
                        <span>Semua</span>
                        <span class="badge bg-light text-dark rounded-pill" style="font-size: 0.65rem;">{{ count($products) }}</span>
                    </button>
                    @foreach($categories as $cat)
                        @php
                            $catCount = $products->where('kategori_id', $cat->id)->count();
                        @endphp
                        @if($catCount > 0)
                            <button type="button" class="category-pill" data-category="{{ $cat->id }}">
                                <span>{{ $cat->nama }}</span>
                                <span class="badge bg-light text-dark rounded-pill" style="font-size: 0.65rem;">{{ $catCount }}</span>
                            </button>
                        @endif
                    @endforeach
                </div>

                <!-- Product Grid Cards -->
                <div class="product-grid-container">
                    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-3 row-cols-xl-4 g-3" id="productGrid">
                        @forelse($products as $prod)
                            <div class="col product-card-col" 
                                 data-category="{{ $prod->kategori_id }}"
                                 data-search="{{ strtolower($prod->nama_barang . ' ' . $prod->kode_barang . ' ' . ($prod->kategori->nama ?? '')) }}">
                                
                                <div class="pos-product-card h-100 d-flex flex-column justify-content-between position-relative"
                                     data-id="{{ $prod->id }}"
                                     data-nama="{{ $prod->nama_barang }}"
                                     data-kode="{{ $prod->kode_barang }}"
                                     data-harga="{{ $prod->harga_jual }}"
                                     data-stok="{{ $prod->stok_saat_ini }}"
                                     data-satuan="{{ $prod->satuan }}"
                                     id="card-prod-{{ $prod->id }}">
                                    
                                    <!-- In-cart Badge -->
                                    <div class="position-absolute top-0 end-0 m-2 pos-cart-badge d-none" id="badge-prod-{{ $prod->id }}">
                                        <span class="badge bg-success rounded-pill shadow-sm d-flex align-items-center gap-1 px-2 py-1" style="font-size: 0.72rem;">
                                            <i class="bi bi-cart-check-fill"></i> <span class="badge-qty">0</span>
                                        </span>
                                    </div>

                                    <div>
                                        <!-- Product Image -->
                                        <div class="position-relative">
                                            @if($prod->gambar_url)
                                                <img src="{{ $prod->gambar_url }}" alt="{{ $prod->nama_barang }}" class="pos-product-img">
                                            @else
                                                <div class="pos-product-img d-flex align-items-center justify-content-center text-muted">
                                                    <i class="bi bi-box-seam fs-1 opacity-25"></i>
                                                </div>
                                            @endif
                                            
                                            <!-- Stok Ribbon -->
                                            <div class="position-absolute bottom-0 start-0 m-1">
                                                <span class="badge {{ $prod->stok_saat_ini <= $prod->stok_minimum ? 'bg-danger' : 'bg-dark bg-opacity-75' }}" style="font-size: 0.65rem;">
                                                    Stok: {{ $prod->stok_saat_ini }} {{ $prod->satuan }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Card Info -->
                                        <div class="p-2">
                                            <div class="d-flex align-items-center justify-content-between gap-1 mb-1">
                                                <span class="badge rounded-pill text-truncate" style="font-size: 0.62rem; max-width: 90px; background-color: {{ $prod->kategori->warna ?? '#10B981' }}20; color: {{ $prod->kategori->warna ?? '#10B981' }};">
                                                    {{ $prod->kategori->nama ?? 'Umum' }}
                                                </span>
                                                <span class="text-muted" style="font-size: 0.65rem;">{{ $prod->kode_barang }}</span>
                                            </div>
                                            <div class="fw-bold text-dark text-truncate-2" style="font-size: 0.8rem; line-height: 1.25; min-height: 2rem;" title="{{ $prod->nama_barang }}">
                                                {{ $prod->nama_barang }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bottom Price & Add Action -->
                                    <div class="p-2 pt-0 border-top bg-light bg-opacity-50 mt-1 d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="fw-bold text-success" style="font-size: 0.85rem;">
                                                {{ formatRupiah($prod->harga_jual) }}
                                            </div>
                                            <div class="text-muted" style="font-size: 0.62rem;">/ {{ $prod->satuan }}</div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-brand-lime rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm" style="width: 28px; height: 28px;" title="Tambah barang">
                                            <i class="bi bi-plus fs-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 py-5 text-center text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                Belum ada barang aktif dengan stok tersedia.
                            </div>
                        @endforelse
                    </div>

                    <!-- Empty State for Filter/Search -->
                    <div id="noProductMatch" class="d-none text-center py-5 text-muted">
                        <i class="bi bi-search fs-2 d-block mb-2 text-secondary opacity-50"></i>
                        <div class="fw-semibold text-dark">Tidak ada produk yang cocok</div>
                        <p class="small text-muted mb-0">Coba ubah kata kunci pencarian atau pilih kategori lain.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Keranjang Belanja & Rincian Pembayaran -->
        <div class="col-lg-5 col-xl-4">
            <div class="card-custom position-sticky" style="top: 85px;">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h5 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                        <i class="bi bi-cart3 text-success"></i>
                        <span>Keranjang Belanja</span>
                        <span class="badge bg-success rounded-pill small" id="cartCountBadge" style="font-size: 0.75rem;">0</span>
                    </h5>
                    <button type="button" class="btn btn-sm btn-link text-danger text-decoration-none p-0 d-none" id="btnClearCart" title="Kosongkan seluruh keranjang">
                        <i class="bi bi-trash"></i> Kosongkan
                    </button>
                </div>

                <!-- Container Item Keranjang -->
                <div class="cart-items-scroll mb-3" id="cartItemsContainer">
                    <!-- Default Empty State -->
                    <div id="emptyCartNotice" class="text-center py-4 text-muted">
                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                            <i class="bi bi-bag-plus fs-2 text-secondary opacity-50"></i>
                        </div>
                        <div class="fw-semibold text-dark small">Keranjang masih kosong</div>
                        <p class="text-muted small m-0" style="font-size: 0.75rem;">Klik produk di katalog untuk menambahkan pesanan</p>
                    </div>

                    <!-- Dynamic Cart Item List -->
                    <div id="cartItemsList" class="d-flex flex-column gap-2"></div>
                </div>

                <!-- Detail Pelanggan & Metode Pembayaran -->
                <div class="border-top pt-3">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark mb-1">Nama Pembeli / Pelanggan</label>
                        <input type="text" name="nama_pelanggan" class="form-control form-control-sm" placeholder="Contoh: Pak Slamet / Pelanggan Umum" value="{{ old('nama_pelanggan', 'Pelanggan Umum') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark mb-1">Metode Pembayaran <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="metode_pembayaran" id="pay_cash" value="cash" checked>
                            <label class="btn btn-outline-success btn-sm flex-fill fw-semibold py-2" for="pay_cash">
                                <i class="bi bi-cash me-1"></i> Tunai
                            </label>

                            <input type="radio" class="btn-check" name="metode_pembayaran" id="pay_transfer" value="transfer">
                            <label class="btn btn-outline-success btn-sm flex-fill fw-semibold py-2" for="pay_transfer">
                                <i class="bi bi-bank me-1"></i> Transfer
                            </label>

                            <input type="radio" class="btn-check" name="metode_pembayaran" id="pay_qris" value="qris">
                            <label class="btn btn-outline-success btn-sm flex-fill fw-semibold py-2" for="pay_qris">
                                <i class="bi bi-qr-code me-1"></i> QRIS
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark mb-1">Diskon Penjualan (Rp)</label>
                        <input type="number" name="diskon" id="diskonInput" class="form-control form-control-sm" value="0" min="0" step="100">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark mb-1">Catatan Transaksi</label>
                        <input type="text" name="catatan" class="form-control form-control-sm" placeholder="Catatan tambahan (opsional)...">
                    </div>

                    <!-- Ringkasan Nominal Total -->
                    <div class="p-3 rounded-3 mb-3" style="background-color: var(--bs-body-bg, #f8fafc); border: 1px solid var(--border-light, #e2e8f0);">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Subtotal:</span>
                            <span class="fw-semibold text-dark" id="displaySubtotal">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Diskon:</span>
                            <span class="fw-semibold text-danger" id="displayDiskon">- Rp 0</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark">Total Bayar:</span>
                            <span class="h4 fw-bold text-success m-0" id="displayTotal">Rp 0</span>
                        </div>
                    </div>

                    <!-- Tombol Checkout -->
                    <button type="submit" id="btnSubmitTx" class="btn btn-brand-lime w-100 py-3 fw-bold fs-6 shadow-sm" disabled>
                        <i class="bi bi-printer me-1"></i> Simpan Transaksi & Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    let cart = [];

    const productGrid = document.getElementById('productGrid');
    const posCards = document.querySelectorAll('.pos-product-card');
    const posCols = document.querySelectorAll('.product-card-col');
    const posSearchInput = document.getElementById('posSearchInput');
    const btnClearSearch = document.getElementById('btnClearSearch');
    const categoryPills = document.querySelectorAll('.category-pill');
    const visibleProductCount = document.getElementById('visibleProductCount');
    const noProductMatch = document.getElementById('noProductMatch');

    const cartItemsContainer = document.getElementById('cartItemsContainer');
    const cartItemsList = document.getElementById('cartItemsList');
    const emptyCartNotice = document.getElementById('emptyCartNotice');
    const cartCountBadge = document.getElementById('cartCountBadge');
    const btnClearCart = document.getElementById('btnClearCart');

    const diskonInput = document.getElementById('diskonInput');
    const displaySubtotal = document.getElementById('displaySubtotal');
    const displayDiskon = document.getElementById('displayDiskon');
    const displayTotal = document.getElementById('displayTotal');
    const btnSubmitTx = document.getElementById('btnSubmitTx');

    let currentCategory = 'all';

    function formatIDR(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    // ==========================================
    // 1. FILTER & PENCARIAN PRODUK
    // ==========================================
    function filterProducts() {
        const query = (posSearchInput.value || '').trim().toLowerCase();
        let visibleCount = 0;

        posCols.forEach(col => {
            const colCategory = col.getAttribute('data-category');
            const colSearch = col.getAttribute('data-search') || '';

            const matchCat = (currentCategory === 'all' || colCategory === currentCategory);
            const matchSearch = (query === '' || colSearch.includes(query));

            if (matchCat && matchSearch) {
                col.classList.remove('d-none');
                visibleCount++;
            } else {
                col.classList.add('d-none');
            }
        });

        visibleProductCount.textContent = visibleCount;
        if (visibleCount === 0) {
            noProductMatch.classList.remove('d-none');
        } else {
            noProductMatch.classList.add('d-none');
        }

        btnClearSearch.classList.toggle('d-none', query === '');
    }

    posSearchInput.addEventListener('input', filterProducts);

    btnClearSearch.addEventListener('click', function () {
        posSearchInput.value = '';
        filterProducts();
        posSearchInput.focus();
    });

    categoryPills.forEach(pill => {
        pill.addEventListener('click', function () {
            categoryPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            currentCategory = this.getAttribute('data-category');
            filterProducts();
        });
    });

    // ==========================================
    // 2. LOGIKA KERANJANG BELANJA (CART)
    // ==========================================
    function updateCardBadges() {
        posCards.forEach(card => {
            const id = parseInt(card.getAttribute('data-id'));
            const badge = document.getElementById('badge-prod-' + id);
            const cartItem = cart.find(i => i.id === id);

            if (cartItem && cartItem.qty > 0) {
                card.classList.add('in-cart');
                if (badge) {
                    badge.classList.remove('d-none');
                    badge.querySelector('.badge-qty').textContent = cartItem.qty;
                }
            } else {
                card.classList.remove('in-cart');
                if (badge) {
                    badge.classList.add('d-none');
                }
            }
        });
    }

    function renderCart() {
        const totalItemsCount = cart.reduce((sum, item) => sum + item.qty, 0);
        cartCountBadge.textContent = totalItemsCount;

        if (cart.length === 0) {
            emptyCartNotice.classList.remove('d-none');
            cartItemsList.innerHTML = '';
            btnClearCart.classList.add('d-none');
            btnSubmitTx.disabled = true;

            displaySubtotal.innerText = formatIDR(0);
            displayDiskon.innerText = '- ' + formatIDR(0);
            displayTotal.innerText = formatIDR(0);
            updateCardBadges();
            return;
        }

        emptyCartNotice.classList.add('d-none');
        btnClearCart.classList.remove('d-none');
        btnSubmitTx.disabled = false;

        let listHtml = '';
        let subtotal = 0;

        cart.forEach((item, index) => {
            const lineTotal = item.harga * item.qty;
            subtotal += lineTotal;

            listHtml += `
                <div class="card p-2 border shadow-xs rounded-3 bg-white">
                    <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                    <input type="hidden" name="items[${index}][jumlah]" value="${item.qty}">
                    
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                        <div>
                            <div class="fw-bold text-dark small line-clamp-1" title="${item.nama}">${item.nama}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">
                                <code>${item.kode}</code> &bull; ${formatIDR(item.harga)} / ${item.satuan}
                            </div>
                        </div>
                        <button type="button" class="btn btn-link text-danger p-0 border-0 btn-remove-item" data-index="${index}" title="Hapus dari keranjang">
                            <i class="bi bi-x-circle-fill"></i>
                        </button>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-1 pt-1 border-top">
                        <!-- Stepper Qty -->
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-outline-secondary btn-stepper btn-minus-qty" data-index="${index}">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" class="form-control form-control-sm text-center item-qty-input px-1" 
                                   style="width: 52px; font-weight: 600;" 
                                   data-index="${index}" 
                                   value="${item.qty}" 
                                   min="1" 
                                   max="${item.stok}">
                            <button type="button" class="btn btn-outline-secondary btn-stepper btn-plus-qty" data-index="${index}">
                                <i class="bi bi-plus"></i>
                            </button>
                            <span class="text-muted ms-1" style="font-size: 0.7rem;">${item.satuan}</span>
                        </div>
                        
                        <!-- Subtotal -->
                        <div class="fw-bold text-dark small">
                            ${formatIDR(lineTotal)}
                        </div>
                    </div>
                </div>
            `;
        });

        cartItemsList.innerHTML = listHtml;

        const diskon = parseFloat(diskonInput.value) || 0;
        const total = Math.max(0, subtotal - diskon);

        displaySubtotal.innerText = formatIDR(subtotal);
        displayDiskon.innerText = '- ' + formatIDR(diskon);
        displayTotal.innerText = formatIDR(total);

        updateCardBadges();

        // Bind delete item
        document.querySelectorAll('.btn-remove-item').forEach(btn => {
            btn.addEventListener('click', function () {
                const idx = parseInt(this.getAttribute('data-index'));
                cart.splice(idx, 1);
                renderCart();
            });
        });

        // Bind stepper minus
        document.querySelectorAll('.btn-minus-qty').forEach(btn => {
            btn.addEventListener('click', function () {
                const idx = parseInt(this.getAttribute('data-index'));
                if (cart[idx].qty > 1) {
                    cart[idx].qty--;
                } else {
                    cart.splice(idx, 1);
                }
                renderCart();
            });
        });

        // Bind stepper plus
        document.querySelectorAll('.btn-plus-qty').forEach(btn => {
            btn.addEventListener('click', function () {
                const idx = parseInt(this.getAttribute('data-index'));
                if (cart[idx].qty < cart[idx].stok) {
                    cart[idx].qty++;
                    renderCart();
                } else {
                    alert('Stok barang hanya tersedia: ' + cart[idx].stok + ' ' + cart[idx].satuan);
                }
            });
        });

        // Bind direct qty input
        document.querySelectorAll('.item-qty-input').forEach(input => {
            input.addEventListener('change', function () {
                const idx = parseInt(this.getAttribute('data-index'));
                let val = parseInt(this.value) || 1;
                if (val > cart[idx].stok) {
                    alert('Jumlah melebihi stok yang tersedia (' + cart[idx].stok + ')');
                    val = cart[idx].stok;
                }
                if (val < 1) val = 1;
                cart[idx].qty = val;
                renderCart();
            });
        });
    }

    // ==========================================
    // 3. EVENT KLIK PADA KARTU BARANG
    // ==========================================
    posCards.forEach(card => {
        card.addEventListener('click', function (e) {
            const id = parseInt(this.getAttribute('data-id'));
            const nama = this.getAttribute('data-nama');
            const kode = this.getAttribute('data-kode');
            const harga = parseFloat(this.getAttribute('data-harga'));
            const stok = parseInt(this.getAttribute('data-stok'));
            const satuan = this.getAttribute('data-satuan');

            if (stok <= 0) {
                alert('Stok barang ' + nama + ' sedang habis.');
                return;
            }

            const existing = cart.find(i => i.id === id);
            if (existing) {
                if (existing.qty + 1 > stok) {
                    alert('Tidak dapat menambah lagi. Stok hanya tersedia ' + stok + ' ' + satuan);
                    return;
                }
                existing.qty++;
            } else {
                cart.push({ id, nama, kode, harga, stok, satuan, qty: 1 });
            }

            renderCart();
        });
    });

    // Tombol Kosongkan Keranjang
    btnClearCart.addEventListener('click', function () {
        if (cart.length > 0 && confirm('Apakah Anda yakin ingin mengosongkan keranjang belanja?')) {
            cart = [];
            renderCart();
        }
    });

    // Input Diskon
    diskonInput.addEventListener('input', function () {
        renderCart();
    });

    // Shortcut Enter pada Search Input: Jika hasil pencarian hanya 1, otomatis tambahkan ke keranjang
    posSearchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const visibleCols = Array.from(posCols).filter(c => !c.classList.contains('d-none'));
            if (visibleCols.length === 1) {
                const card = visibleCols[0].querySelector('.pos-product-card');
                if (card) {
                    card.click();
                    posSearchInput.value = '';
                    filterProducts();
                }
            }
        }
    });
});
</script>
@endpush
