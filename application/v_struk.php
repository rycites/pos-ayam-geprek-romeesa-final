<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan - #<?= $transaksi['no_faktur']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Courier New', Courier, monospace; /* Khas font struk */
        }
        .receipt-card {
            max-width: 400px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .dashed-line {
            border-top: 2px dashed #dee2e6;
            margin: 15px 0;
        }
        @media print {
            body {
                background: #fff;
            }
            .no-print {
                display: none !important;
            }
            .receipt-card {
                box-shadow: none;
                margin: 0;
                padding: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="receipt-card">
            <!-- Header Toko -->
            <div class="text-center mb-3">
                <h4 class="fw-bold mb-1" style="font-family: 'Segoe UI', sans-serif; letter-spacing: -0.5px;">AYAM GEPREK ROMEESA</h4>
                <p class="text-muted small mb-0">Jl. Raya Kuliner No. 123, Kota Anda</p>
                <p class="text-muted small">Telp: 0812-3456-7890</p>
            </div>

            <div class="dashed-line"></div>

            <!-- Info Transaksi -->
            <div class="small mb-3">
                <div class="d-flex justify-content-between">
                    <span>No Faktur:</span>
                    <strong class="text-dark"><?= $transaksi['no_faktur']; ?></strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Tanggal:</span>
                    <span><?= $transaksi['tanggal']; ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Meja / Pemesan:</span>
                    <strong class="text-danger"><?= $transaksi['nomor_meja']; ?></strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Kasir:</span>
                    <span><?= isset($nama_kasir) ? $nama_kasir : 'Mandiri (QR)'; ?></span>
                </div>
            </div>

            <div class="dashed-line"></div>

            <!-- Daftar Item Pesanan -->
            <div class="mb-3">
                <table class="table table-sm table-borderless small mb-0">
                    <thead>
                        <tr class="border-bottom">
                            <th class="ps-0">Menu</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end pe-0">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($detail_transaksi as $item): ?>
                            <tr>
                                <td class="ps-0">
                                    <?= $item['nama_menu']; ?><br>
                                    <span class="text-muted" style="font-size: 11px;">@ Rp <?= number_format($item['harga'], 0, ',', '.'); ?></span>
                                </td>
                                <td class="text-center align-middle"><?= $item['jumlah']; ?></td>
                                <td class="text-end align-middle pe-0">Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="dashed-line"></div>

            <!-- Rincian Pembayaran -->
            <div class="small mb-4">
                <div class="d-flex justify-content-between mb-1">
                    <span>Total Tagihan:</span>
                    <strong class="text-success fs-6">Rp <?= number_format($transaksi['total_bayar'], 0, ',', '.'); ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span>Metode Pembayaran:</span>
                    <span class="fw-bold"><?= $transaksi['metode_pembayaran']; ?></span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span>Status Bayar:</span>
                    <span class="badge bg-<?= ($transaksi['status_bayar'] == 'Lunas' || $transaksi['status_bayar'] == 'lunas') ? 'success' : 'warning'; ?>">
                        <?= ucfirst($transaksi['status_bayar']); ?>
                    </span>
                </div>
                <?php if($transaksi['metode_pembayaran'] == 'Cash'): ?>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Uang Tunai:</span>
                        <span>Rp <?= number_format($transaksi['uang_bayar'], 0, ',', '.'); ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Kembalian:</span>
                        <span>Rp <?= number_format($transaksi['uang_kembalian'], 0, ',', '.'); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Footer Struk -->
            <div class="text-center small text-muted mb-4" style="font-family: 'Segoe UI', sans-serif;">
                <p class="mb-1">*** TERIMA KASIH TELAH BERKUNJUNG ***</p>
                <p class="mb-0" style="font-size: 11px;">Pesanan Anda sedang dipersiapkan oleh dapur kami.</p>
            </div>

            <!-- Tombol Aksi (Tidak Ikut Tercetak) -->
            <div class="d-grid gap-2 no-print">
                <button onclick="window.print()" class="btn btn-dark rounded-pill fw-bold py-2">
                    <i class="fa-solid fa-print me-2"></i> Cetak Struk
                </button>
                <a href="<?= site_url($this->session->userdata('role') == 'kasir' ? 'cashier' : 'pelanggan/meja/' . filter_var($transaksi['nomor_meja'], FILTER_SANITIZE_NUMBER_INT)); ?>" class="btn btn-outline-secondary rounded-pill fw-semibold py-2">
                    <i class="fa-solid fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

</body>
</html>