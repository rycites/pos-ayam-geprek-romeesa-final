
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// =====================================================
// DATA TRANSAKSI
// =====================================================
$transaksi = isset($transaksi) && is_array($transaksi)
    ? $transaksi
    : [];

$detail_transaksi = isset($detail_transaksi) && is_array($detail_transaksi)
    ? $detail_transaksi
    : (isset($detail) && is_array($detail) ? $detail : []);

$nomor_meja = $transaksi['nomor_meja'] ?? '';
$no_faktur = $transaksi['no_faktur'] ?? '';

$id_trx = $transaksi['id_transaksi']
    ?? $transaksi['id']
    ?? '';

// =====================================================
// NOMOR MEJA
// =====================================================
preg_match('/\d+/', $nomor_meja !== '' ? $nomor_meja : '1', $match);

$no_m = isset($match[0])
    ? (int) $match[0]
    : 1;

// =====================================================
// STATUS PEMBAYARAN
// =====================================================
$status_bayar = strtolower(trim($transaksi['status_bayar'] ?? ''));

$lunas = ($status_bayar === 'lunas');

// =====================================================
// CEK SEMUA ITEM SELESAI
// =====================================================
$is_semua_selesai = !empty($detail_transaksi);

foreach ($detail_transaksi as $dt) {
    $status_item = strtolower(trim($dt['status'] ?? ''));

    if ($status_item !== 'selesai') {
        $is_semua_selesai = false;
        break;
    }
}

// =====================================================
// LABEL STATUS ITEM
// =====================================================
$label_status = [
    'menunggu_kasir' => [
        'Menunggu konfirmasi kasir',
        'is-neutral',
        'fa-clock'
    ],
    'pending' => [
        'Menunggu antrean dapur',
        'is-neutral',
        'fa-hourglass-half'
    ],
    'proses' => [
        'Sedang dimasak',
        'is-warn',
        'fa-fire'
    ],
    'diproses' => [
        'Sedang dimasak',
        'is-warn',
        'fa-fire'
    ],
    'selesai' => [
        'Siap disajikan',
        'is-ok',
        'fa-circle-check'
    ]
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Status Pesanan - <?= html_escape($no_faktur); ?> | Ayam Geprek Romeesa</title>

    <?php if (!$is_semua_selesai): ?>
        <meta http-equiv="refresh" content="5">
    <?php endif; ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="<?= base_url('assets/css/theme.css'); ?>">
</head>

<body class="py-4">

<div class="container" style="max-width:520px;">

    <div class="card overflow-hidden">

        <!-- HEADER -->
        <div class="panel-head text-center">

            <span class="badge-soft is-brand">
                <i class="fa-solid fa-receipt" aria-hidden="true"></i>
                Struk &amp; status meja
            </span>

            <h1 class="h4 fw-extrabold mt-3 mb-1">
                Status Pesanan
            </h1>

            <p class="text-muted small mb-0">
                No. faktur
                <strong><?= html_escape($no_faktur); ?></strong>
            </p>

            <p class="small fw-bold mb-0">
                <?= html_escape($nomor_meja); ?>
            </p>

        </div>

        <!-- ISI STATUS -->
        <div class="panel-body">

            <!-- STATUS PEMBAYARAN -->
            <span class="stat-label d-block mb-2">
                Status pembayaran
            </span>

            <?php if ($lunas): ?>

                <div class="alert alert-success mb-4" role="status">

                    <i class="fa-solid fa-circle-check me-1" aria-hidden="true"></i>

                    <strong>Pembayaran berhasil dikonfirmasi!</strong>

                    <div class="small mt-1">
                        Pesanan Anda telah diteruskan ke dapur.
                    </div>

                </div>

            <?php else: ?>

                <div class="alert alert-warning mb-4" role="status">

                    <i class="fa-solid fa-clock me-1" aria-hidden="true"></i>

                    <strong>Menunggu konfirmasi kasir</strong>

                    <div class="small mt-1">
                        Mohon tunggu, kasir sedang memeriksa pembayaran Anda.
                    </div>

                </div>

            <?php endif; ?>

            <!-- STATUS DAPUR -->
            <span class="stat-label d-block mb-2">
                Proses dapur
            </span>

            <div class="list-rows">

                <?php if (!empty($detail_transaksi)): ?>

                    <?php foreach ($detail_transaksi as $item): ?>

                        <?php
                        $status_asli = strtolower(trim($item['status'] ?? ''));

                        if (!$lunas) {
                            $label = 'Menunggu konfirmasi kasir';
                            $kelas = 'is-neutral';
                            $ikon = 'fa-clock';

                        } elseif (isset($label_status[$status_asli])) {
                            $label = $label_status[$status_asli][0];
                            $kelas = $label_status[$status_asli][1];
                            $ikon = $label_status[$status_asli][2];

                        } else {
                            $label = $status_asli !== ''
                                ? ucfirst($status_asli)
                                : 'Status tidak diketahui';

                            $kelas = 'is-neutral';
                            $ikon = 'fa-circle-question';
                        }

                        $nama_menu = $item['nama_menu'] ?? 'Menu';

                        $nama_sambal = trim((string) ($item['nama_sambal'] ?? ''));

                        $level_pedas = isset($item['level_pedas'])
                            ? (int) $item['level_pedas']
                            : 0;

                        $jumlah = isset($item['jumlah'])
                            ? (int) $item['jumlah']
                            : 0;
                        ?>

                        <div class="list-row">

                            <div class="me-auto">

                                <div class="row-title">
                                    <?= html_escape($nama_menu); ?>
                                </div>

                                <?php if ($nama_sambal !== '' || $level_pedas > 0): ?>
                                    <div class="row-sub">
                                        <?php if ($nama_sambal !== ''): ?>
                                            Sambal: <?= html_escape($nama_sambal); ?>
                                        <?php endif; ?>

                                        <?php if ($nama_sambal !== '' && $level_pedas > 0): ?>
                                            &middot;
                                        <?php endif; ?>

                                        <?php if ($level_pedas > 0): ?>
                                            Level <?= $level_pedas; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="row-sub">
                                    Jumlah: <?= $jumlah; ?>
                                </div>

                            </div>

                            <span class="badge-soft <?= $kelas; ?>">

                                <i class="fa-solid <?= $ikon; ?> me-1" aria-hidden="true"></i>

                                <?= html_escape($label); ?>

                            </span>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p class="text-muted small mb-0">
                        Rincian item tidak ditemukan untuk pesanan ini.
                    </p>

                <?php endif; ?>

            </div>

            <!-- INFORMASI REFRESH -->
            <?php if (!$is_semua_selesai): ?>

                <div class="alert alert-info mt-4 mb-0" role="status">

                    <i class="fa-solid fa-circle-info me-1" aria-hidden="true"></i>

                    Status pesanan diperbarui otomatis setiap 5 detik.

                </div>

            <?php else: ?>

                <div class="alert alert-success mt-4 mb-0" role="status">

                    <i class="fa-solid fa-check-circle me-1" aria-hidden="true"></i>

                    Semua menu pesanan telah selesai dimasak.

                </div>

            <?php endif; ?>

        </div>

        <!-- FOOTER / TOMBOL -->
        <div class="panel-foot d-grid gap-2">

            <?php if (!empty($id_trx)): ?>

                <a
                    href="<?= site_url('cashier/struk/' . $id_trx); ?>"
                    class="btn btn-light"
                    target="_blank"
                    rel="noopener"
                >
                    <i class="fa-solid fa-receipt me-2" aria-hidden="true"></i>
                    Lihat struk bukti pesanan
                </a>

            <?php endif; ?>

            <?php if ($is_semua_selesai): ?>

                <form action="<?= site_url('pelanggan/reset_sesi_meja/' . $no_m); ?>" method="POST">
                    <input type="hidden"
                           name="<?= $this->security->get_csrf_token_name(); ?>"
                           value="<?= $this->security->get_csrf_hash(); ?>">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-utensils me-2" aria-hidden="true"></i>
                        Selesai, buat pesanan baru
                    </button>
                </form>

                <a
                    href="<?= site_url('pelanggan/meja/' . $no_m); ?>"
                    class="btn btn-light"
                >
                    <i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>
                    Kembali ke menu
                </a>

            <?php else: ?>

                <a
                    href="<?= site_url('pelanggan/meja/' . $no_m); ?>"
                    class="btn btn-primary"
                >
                    <i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>
                    Kembali ke menu utama
                </a>

            <?php endif; ?>

        </div>

    </div>

</div>

</body>
</html>
