<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan Berhasil - Ayam Geprek Romeesa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/theme.css'); ?>">
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">

<div class="card p-4 text-center m-3" style="max-width: 460px; width: 100%;">
    <div class="mx-auto mb-3"
         style="width: 64px; height: 64px; border-radius: 999px; background: var(--ok-soft); color: var(--ok); display: grid; place-items: center; font-size: 1.6rem;">
        <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
    </div>

    <h1 class="h4 fw-extrabold mb-2">Pesanan berhasil dikirim</h1>

    <p class="text-muted small mb-4">
        Pesanan untuk <strong>Meja <?= html_escape($no_meja); ?></strong>
        dengan no. faktur <strong><?= html_escape($no_faktur); ?></strong> sudah masuk sistem.
        Makanan akan segera disiapkan, silakan tunggu di meja Anda.
    </p>

    <div class="d-grid gap-2">
        <?php if (isset($id_transaksi)): ?>
            <a href="<?= site_url('pelanggan/status/' . $id_transaksi); ?>" class="btn btn-primary">
                <i class="fa-solid fa-clock-rotate-left me-2" aria-hidden="true"></i> Cek status pesanan
            </a>

            <a href="<?= site_url('cashier/struk/' . $id_transaksi); ?>" class="btn btn-light" target="_blank" rel="noopener">
                <i class="fa-solid fa-receipt me-2" aria-hidden="true"></i> Lihat struk bukti pesanan
            </a>
        <?php endif; ?>

        <a href="<?= site_url('pelanggan/meja/' . $no_meja); ?>" class="btn btn-light">
            <i class="fa-solid fa-plus me-2" aria-hidden="true"></i> Pesan menu lain
        </a>
    </div>
</div>

</body>
</html>
