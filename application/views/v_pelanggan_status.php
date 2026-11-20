<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$nomor_meja = isset($transaksi['nomor_meja']) ? $transaksi['nomor_meja'] : '';
$no_faktur  = isset($transaksi['no_faktur']) ? $transaksi['no_faktur'] : '';
$lunas      = isset($transaksi['status_bayar']) && strtolower($transaksi['status_bayar']) === 'lunas';
$id_trx     = isset($transaksi['id_transaksi']) ? $transaksi['id_transaksi'] : (isset($transaksi['id']) ? $transaksi['id'] : '');

// Nomor meja tersimpan dalam teks seperti "Meja 3 (Budi)"
preg_match('/\d+/', $nomor_meja !== '' ? $nomor_meja : '1', $match);
$no_m = isset($match[0]) ? $match[0] : 1;

// Tombol pesanan baru hanya muncul setelah semua item selesai dimasak
$is_semua_selesai = !empty($detail_transaksi);
foreach ($detail_transaksi as $dt) {
    if ($dt['status'] !== 'selesai') {
        $is_semua_selesai = false;
        break;
    }
}

$label_status = [
    'pending'        => ['Menunggu antrean', 'is-neutral'],
    'menunggu_kasir' => ['Menunggu konfirmasi kasir', 'is-neutral'],
    'proses'         => ['Sedang dimasak', 'is-warn'],
    'selesai'        => ['Siap disajikan', 'is-ok'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status Pesanan - <?= html_escape($no_faktur); ?> | Ayam Geprek Romeesa</title>
    <meta http-equiv="refresh" content="10">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/theme.css'); ?>">
</head>
<body class="py-4">

<div class="container" style="max-width: 520px;">
    <div class="card overflow-hidden">

        <div class="panel-head text-center">
            <span class="badge-soft is-brand">
                <i class="fa-solid fa-receipt" aria-hidden="true"></i> Struk &amp; status meja
            </span>
            <h1 class="h4 fw-extrabold mt-3 mb-1">Pesanan aktif</h1>
            <p class="text-muted small mb-0">No. faktur <strong><?= html_escape($no_faktur); ?></strong></p>
            <p class="small fw-bold mb-0"><?= html_escape($nomor_meja); ?></p>
        </div>

        <div class="panel-body">
            <span class="stat-label d-block mb-2">Status pembayaran</span>
            <?php if ($lunas): ?>
                <div class="alert alert-success mb-4" role="status">
                    <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                    <span>Lunas, pembayaran sudah diterima.</span>
                </div>
            <?php else: ?>
                <div class="alert alert-warning mb-4" role="status">
                    <i class="fa-solid fa-clock" aria-hidden="true"></i>
                    <span>Menunggu konfirmasi kasir atau pembayaran di kasir.</span>
                </div>
            <?php endif; ?>

            <span class="stat-label d-block mb-2">Proses dapur</span>
            <div class="list-rows">
                <?php if (!empty($detail_transaksi)): ?>
                    <?php foreach ($detail_transaksi as $item): ?>
                        <?php
                            $kunci  = isset($label_status[$item['status']]) ? $item['status'] : '';
                            $label  = $kunci ? $label_status[$kunci][0] : ucfirst(html_escape($item['status']));
                            $kelas  = $kunci ? $label_status[$kunci][1] : 'is-neutral';
                        ?>
                        <div class="list-row">
                            <div class="me-auto">
                                <div class="row-title"><?= html_escape($item['nama_menu']); ?></div>
                                <div class="row-sub">Jumlah: <?= (int) $item['jumlah']; ?></div>
                            </div>
                            <span class="badge-soft <?= $kelas; ?>">
                                <span class="dot" aria-hidden="true"></span> <?= $label; ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted small mb-0">Rincian item tidak ditemukan untuk pesanan ini.</p>
                <?php endif; ?>
            </div>

            <div class="alert alert-info mt-4 mb-0" role="status">
                <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
                <span>Halaman ini menyegar sendiri setiap 10 detik, tidak perlu muat ulang manual.</span>
            </div>
        </div>

        <div class="panel-foot d-grid gap-2">
            <?php if (!empty($id_trx)): ?>
                <a href="<?= site_url('cashier/struk/' . $id_trx); ?>" class="btn btn-light" target="_blank" rel="noopener">
                    <i class="fa-solid fa-receipt me-2" aria-hidden="true"></i> Lihat struk bukti pesanan
                </a>
            <?php endif; ?>

            <?php if ($is_semua_selesai): ?>
                <a href="<?= site_url('pelanggan/reset_sesi_meja/' . $no_m); ?>" class="btn btn-primary">
                    <i class="fa-solid fa-utensils me-2" aria-hidden="true"></i> Selesai, buat pesanan baru
                </a>
                <a href="<?= site_url('pelanggan/meja/' . $no_m); ?>" class="btn btn-light">
                    <i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i> Kembali ke menu
                </a>
            <?php else: ?>
                <a href="<?= site_url('pelanggan/meja/' . $no_m); ?>" class="btn btn-primary">
                    <i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i> Kembali ke menu utama
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
