<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk Pesanan - <?= html_escape($transaksi['no_faktur']); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/theme.css'); ?>">
</head>
<body class="py-4">

<div class="receipt-card receipt-mono">
    <div class="text-center mb-3">
        <i class="fa-solid fa-utensils" style="font-size: 1.5rem; color: var(--brand);" aria-hidden="true"></i>
        <h4 class="fw-extrabold mb-1 mt-2">AYAM GEPREK ROMEESA</h4>
        <p class="text-muted small mb-0">Jl. Raya Kuliner No. 123, Kota Anda</p>
        <p class="text-muted small mb-0">Telp: 0812-3456-7890</p>
    </div>

    <div class="dashed-line"></div>

    <div class="sum-row">
        <span>No faktur</span>
        <strong><?= html_escape($transaksi['no_faktur']); ?></strong>
    </div>
    <div class="sum-row">
        <span>Tanggal</span>
        <span><?= html_escape($transaksi['tanggal']); ?></span>
    </div>
    <div class="sum-row">
        <span>Meja / pemesan</span>
        <strong><?= html_escape($transaksi['nomor_meja']); ?></strong>
    </div>
    <div class="sum-row">
        <span>Kasir</span>
        <span><?= isset($nama_kasir) ? html_escape($nama_kasir) : 'Mandiri (QR)'; ?></span>
    </div>

    <div class="dashed-line"></div>

    <table class="table table-sm table-borderless small mb-0">
        <thead>
            <tr>
                <th>Menu</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detail_transaksi as $item): ?>
                <tr>
                    <td>
                        <?= html_escape($item['nama_menu']); ?><br>
                        <span class="text-muted" style="font-size: 11px;">@ Rp <?= number_format($item['harga'], 0, ',', '.'); ?></span>
                    </td>
                    <td class="text-center align-middle num"><?= (int) $item['jumlah']; ?></td>
                    <td class="text-end align-middle num">Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="dashed-line"></div>

    <div class="sum-row is-total">
        <span>Total tagihan</span>
        <span class="value money">Rp <?= number_format($transaksi['total_bayar'], 0, ',', '.'); ?></span>
    </div>
    <div class="sum-row">
        <span>Metode pembayaran</span>
        <span class="fw-bold"><?= html_escape($transaksi['metode_pembayaran']); ?></span>
    </div>
    <div class="sum-row">
        <span>Status bayar</span>
        <?php $lunas = strtolower($transaksi['status_bayar']) === 'lunas'; ?>
        <span class="badge-soft <?= $lunas ? 'is-ok' : 'is-warn'; ?>">
            <span class="dot" aria-hidden="true"></span> <?= ucfirst(html_escape($transaksi['status_bayar'])); ?>
        </span>
    </div>

    <?php if ($transaksi['metode_pembayaran'] === 'Cash'): ?>
        <div class="sum-row">
            <span>Uang tunai</span>
            <span>Rp <?= number_format($transaksi['uang_bayar'], 0, ',', '.'); ?></span>
        </div>
        <div class="sum-row">
            <span>Kembalian</span>
            <span>Rp <?= number_format($transaksi['uang_kembalian'], 0, ',', '.'); ?></span>
        </div>
    <?php endif; ?>

    <div class="dashed-line"></div>

    <div class="text-center small text-muted">
        <p class="mb-1">*** TERIMA KASIH TELAH BERKUNJUNG ***</p>
        <p class="mb-0" style="font-size: 11px;">Pesanan Anda sedang dipersiapkan oleh dapur kami.</p>
    </div>

    <div class="d-grid gap-2 no-print mt-4">
        <button onclick="window.print()" class="btn btn-primary fw-bold">
            <i class="fa-solid fa-print me-2" aria-hidden="true"></i> Cetak struk
        </button>
        <a href="<?= site_url($this->session->userdata('role') === 'kasir' ? 'cashier' : 'pelanggan/meja/' . filter_var($transaksi['nomor_meja'], FILTER_SANITIZE_NUMBER_INT)); ?>"
           class="btn btn-light fw-semibold">
            <i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i> Kembali
        </a>
    </div>
</div>

</body>
</html>
