<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Laporan Penjualan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/theme.css'); ?>">
</head>
<body onload="window.print()">

<div class="container my-4">
    <div class="text-center mb-4">
        <h1 class="h3 fw-extrabold mb-1">AYAM GEPREK ROMEESA</h1>
        <p class="text-muted mb-0">Laporan rekapitulasi penjualan restoran</p>
    </div>

    <div class="card overflow-hidden mb-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 48px;">No</th>
                        <th>No. faktur</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th class="num text-end">Total belanja</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grand_total = 0;
                    if (!empty($laporan)):
                        $no = 1;
                        foreach ($laporan as $l):
                            $grand_total += $l['total_bayar'];
                    ?>
                        <tr>
                            <td class="text-muted num"><?= $no++; ?></td>
                            <td><?= html_escape($l['no_faktur']); ?></td>
                            <td class="text-muted small"><?= html_escape($l['tanggal']); ?></td>
                            <td><?= $l['nama_kasir'] ? html_escape($l['nama_kasir']) : 'Kasir #' . (int) $l['id_kasir']; ?></td>
                            <td class="num text-end fw-bold">Rp <?= number_format($l['total_bayar'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Tidak ada transaksi pada periode ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="panel-foot d-flex align-items-center justify-content-between">
            <span class="fw-bold">Total pendapatan</span>
            <span class="fw-extrabold fs-5 money">Rp <?= number_format($grand_total, 0, ',', '.'); ?></span>
        </div>
    </div>

    <div class="text-end no-print">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fa-solid fa-print me-1" aria-hidden="true"></i> Cetak ulang
        </button>
        <button onclick="window.close()" class="btn btn-light">Tutup</button>
    </div>
</div>

</body>
</html>
