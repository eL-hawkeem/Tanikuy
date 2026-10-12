<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur {{ $transaction->nomor_transaksi }} — Tanikuy</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 20px;
            color: #0B130F;
            background: #ffffff;
            font-size: 13px;
        }
        .invoice-box {
            max-width: 600px;
            margin: auto;
            border: 1px solid #E9EFEF;
            padding: 25px;
            border-radius: 12px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #072F1F;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .brand {
            font-size: 24px;
            font-weight: 800;
            color: #072F1F;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .meta-table td {
            padding: 4px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background: #F4F6F5;
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid #CBD5E1;
            font-size: 12px;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #E9EFEF;
        }
        .text-end { text-align: right; }
        .total-box {
            background: #F4F6F5;
            padding: 12px 15px;
            border-radius: 8px;
            margin-top: 15px;
        }
        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 11px;
            color: #6C7E75;
        }
        .print-btn {
            background: #072F1F;
            color: #B4F105;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 20px;
        }
        @media print {
            .no-print { display: none; }
            .invoice-box { border: none; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center;">
        <button class="print-btn" onclick="window.print()">🖨️ Cetak / Simpan Faktur (PDF)</button>
    </div>

    <div class="invoice-box">
        <div class="header">
            <div>
                <div class="brand">Tanikuy</div>
                <div style="color: #6C7E75; font-size: 12px;">Solusi Cerdas Inventory UMKM Pertanian</div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 16px; font-weight: bold;">FAKTUR PENJUALAN</div>
                <div style="color: #072F1F; font-weight: bold;">{{ $transaction->nomor_transaksi }}</div>
            </div>
        </div>

        <table class="meta-table">
            <tr>
                <td style="width: 50%;"><strong>Tanggal:</strong> {{ $transaction->tanggal->translatedFormat('d F Y, H:i') }}</td>
                <td style="width: 50%;"><strong>Pembeli:</strong> {{ $transaction->nama_pelanggan ?? 'Pelanggan Umum' }}</td>
            </tr>
            <tr>
                <td><strong>Metode Bayar:</strong> {{ strtoupper($transaction->metode_pembayaran) }}</td>
                <td><strong>Kasir:</strong> {{ $transaction->user->name ?? 'Kasir Tanikuy' }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Status:</strong> {{ strtoupper($transaction->status) }}</td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Item Barang</th>
                    <th>Harga</th>
                    <th style="text-align: center;">Qty</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->details as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product->nama_barang ?? 'Item' }}</strong><br>
                            <small style="color: #6C7E75;">{{ $item->product->kode_barang ?? '' }}</small>
                        </td>
                        <td>{{ formatRupiah($item->harga_satuan) }}</td>
                        <td style="text-align: center;">{{ $item->jumlah }} {{ $item->product->satuan ?? '' }}</td>
                        <td class="text-end">{{ formatRupiah($item->subtotal) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-box">
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span>Subtotal:</span>
                <span>{{ formatRupiah($transaction->subtotal) }}</span>
            </div>
            @if($transaction->diskon > 0)
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px; color: #EF4444;">
                    <span>Diskon:</span>
                    <span>- {{ formatRupiah($transaction->diskon) }}</span>
                </div>
            @endif
            <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: bold; border-top: 1px solid #CBD5E1; padding-top: 6px;">
                <span>Total Bayar:</span>
                <span style="color: #072F1F;">{{ formatRupiah($transaction->total) }}</span>
            </div>
        </div>

        <div class="footer">
            Terima kasih telah berbelanja produk pertanian di Tanikuy.<br>
            Barang yang sudah dibeli tidak dapat ditukar kecuali ada perjanjian sebelumnya.
        </div>
    </div>
</body>
</html>
