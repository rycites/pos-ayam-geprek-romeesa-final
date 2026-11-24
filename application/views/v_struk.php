
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
// =====================================================
// DATA TRANSAKSI
// =====================================================
$transaksi = isset($transaksi) && is_array($transaksi)
    ? $transaksi
    : [];

$detail_transaksi = isset($detail_transaksi) && is_array($detail_transaksi)
    ? $detail_transaksi
    : [];

$no_faktur = $transaksi['no_faktur'] ?? '-';
$tanggal = $transaksi['tanggal'] ?? '-';
$nomor_meja = $transaksi['nomor_meja'] ?? '-';
$total_bayar = (float) ($transaksi['total_bayar'] ?? 0);
$metode_pembayaran = $transaksi['metode_pembayaran'] ?? '-';
$status_bayar = $transaksi['status_bayar'] ?? 'Belum diketahui';

$uang_bayar = (float) ($transaksi['uang_bayar'] ?? 0);
$uang_kembalian = (float) ($transaksi['uang_kembalian'] ?? 0);

$lunas = strtolower(trim($status_bayar)) === 'lunas';

$nama_kasir_tampil = !empty($nama_kasir)
    ? $nama_kasir
    : 'Mandiri (QR)';

// =====================================================
// URL KEMBALI
// =====================================================
$role = $this->session->userdata('role');

if ($role === 'kasir') {
    $url_kembali = site_url('cashier');
} else {
    // Ambil nomor meja dari format "Meja 1 (Nama)"
    preg_match('/\d+/', $nomor_meja !== '-' ? $nomor_meja : '1', $match);

    $nomor_meja_url = isset($match[0])
        ? (int) $match[0]
        : 1;

    $url_kembali = site_url('pelanggan/meja/' . $nomor_meja_url);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Struk Pesanan - <?= html_escape($no_faktur); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="<?= base_url('assets/css/theme.css'); ?>">

    <style>
        @media print {
            body {
                padding: 0 !important;
                background: #fff !important;
            }

            .receipt-card {
                box-shadow: none !important;
                border: none !important;
                margin: 0 auto !important;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body class="py-4">

<div class="receipt-card receipt-mono">

    <!-- HEADER RESTORAN -->
    <div class="text-center mb-3">

        <i
            class="fa-solid fa-utensils"
            style="font-size: 1.5rem; color: var(--brand);"
            aria-hidden="true"
        ></i>

        <h4 class="fw-extrabold mb-1 mt-2">
            AYAM GEPREK ROMEESA
        </h4>

        <p class="text-muted small mb-0">
            Jl. Raya Kuliner No. 123, Kota Anda
        </p>

        <p class="text-muted small mb-0">
            Telp: 0812-3456-7890
        </p>

    </div>

    <div class="dashed-line"></div>

    <!-- INFORMASI TRANSAKSI -->
    <div class="sum-row">
        <span>No. faktur</span>
        <strong><?= html_escape($no_faktur); ?></strong>
    </div>

    <div class="sum-row">
        <span>Tanggal</span>
        <span><?= html_escape($tanggal); ?></span>
    </div>

    <div class="sum-row">
        <span>Meja / pemesan</span>
        <strong><?= html_escape($nomor_meja); ?></strong>
    </div>

    <div class="sum-row">
        <span>Kasir</span>
        <span><?= html_escape($nama_kasir_tampil); ?></span>
    </div>

    <div class="dashed-line"></div>

    <!-- DETAIL MENU -->
    <table class="table table-sm table-borderless small mb-0">

        <thead>
            <tr>
                <th>Menu</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Subtotal</th>
            </tr>
        </thead>

        <tbody>

            <?php if (!empty($detail_transaksi)): ?>

                <?php foreach ($detail_transaksi as $item): ?>

                    <?php
                    $nama_menu = $item['nama_menu'] ?? 'Menu';
                    $harga = (float) ($item['harga'] ?? 0);
                    $jumlah = (int) ($item['jumlah'] ?? 0);
                    $subtotal = (float) ($item['subtotal'] ?? ($harga * $jumlah));
                    $nama_sambal = trim((string) ($item['nama_sambal'] ?? ''));
                    $level_pedas = isset($item['level_pedas'])
                        ? (int) $item['level_pedas']
                        : 0;
                    ?>

                    <tr>

                        <td>
                            <?= html_escape($nama_menu); ?><br>

                            <span class="text-muted" style="font-size: 11px;">
                                @ Rp <?= number_format($harga, 0, ',', '.'); ?>
                            </span>

                            <?php if ($nama_sambal !== '' || $level_pedas > 0): ?>
                                <br>
                                <span class="text-muted" style="font-size: 11px;">
                                    <?php if ($nama_sambal !== ''): ?>
                                        Sambal: <?= html_escape($nama_sambal); ?>
                                    <?php endif; ?>

                                    <?php if ($nama_sambal !== '' && $level_pedas > 0): ?>
                                        &middot;
                                    <?php endif; ?>

                                    <?php if ($level_pedas > 0): ?>
                                        Level <?= $level_pedas; ?>
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center align-middle num">
                            <?= $jumlah; ?>
                        </td>

                        <td class="text-end align-middle num">
                            Rp <?= number_format($subtotal, 0, ',', '.'); ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="3" class="text-center text-muted">
                        Detail pesanan tidak tersedia.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

    <div class="dashed-line"></div>

    <!-- TOTAL PEMBAYARAN -->
    <div class="sum-row is-total">
        <span>Total tagihan</span>

        <span class="value money">
            Rp <?= number_format($total_bayar, 0, ',', '.'); ?>
        </span>
    </div>

    <div class="sum-row">
        <span>Metode pembayaran</span>
        <strong><?= html_escape($metode_pembayaran); ?></strong>
    </div>

    <div class="sum-row">
        <span>Status bayar</span>

        <span class="badge-soft <?= $lunas ? 'is-ok' : 'is-warn'; ?>">

            <span class="dot" aria-hidden="true"></span>

            <?= html_escape(ucfirst($status_bayar)); ?>

        </span>
    </div>

    <!-- PEMBAYARAN TUNAI -->
    <?php if (strtolower(trim($metode_pembayaran)) === 'cash'): ?>

        <div class="sum-row">
            <span>Uang tunai</span>

            <span>
                Rp <?= number_format($uang_bayar, 0, ',', '.'); ?>
            </span>
        </div>

        <div class="sum-row">
            <span>Kembalian</span>

            <span>
                Rp <?= number_format($uang_kembalian, 0, ',', '.'); ?>
            </span>
        </div>

    <?php endif; ?>

    <div class="dashed-line"></div>

    <!-- FOOTER STRUK -->
    <div class="text-center small text-muted">

        <p class="mb-1">
            *** TERIMA KASIH TELAH BERKUNJUNG ***
        </p>

        <p class="mb-0" style="font-size: 11px;">
            Pesanan Anda sedang dipersiapkan oleh dapur kami.
        </p>

    </div>

    <!-- TOMBOL -->
    <div class="d-grid gap-2 no-print mt-4">

        <button
            type="button"
            onclick="window.print()"
            class="btn btn-primary fw-bold"
        >
            <i class="fa-solid fa-print me-2" aria-hidden="true"></i>
            Cetak struk
        </button>

        <a
            href="<?= $url_kembali; ?>"
            class="btn btn-light fw-semibold"
        >
            <i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>
            Kembali
        </a>

    </div>

</div>

</body>
</html>
