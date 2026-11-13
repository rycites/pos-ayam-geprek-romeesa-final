<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container mt-4">
        <div class="text-center mb-4">
            <h3 class="fw-bold">AYAM GEPREK ROMEESA</h3>
            <p class="text-muted mb-0">Laporan Rekapitulasi Penjualan Restoran</p>
            <hr>
        </div>

        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>No. Faktur</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
                    <th>Total Belanja</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $grand_total = 0;
                if(!empty($laporan)): 
                    $no = 1;
                    foreach($laporan as $l):
                        $grand_total += $l['total_bayar']; // Diperbaiki jadi total_bayar
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $l['no_faktur']; ?></td>
                        <td><?= $l['tanggal']; ?></td>
                        <td><?= $l['nama_kasir'] ? $l['nama_kasir'] : 'Kasir ID: '.$l['id_kasir']; ?></td>
                        <td class="fw-bold">Rp <?= number_format($l['total_bayar'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center mt-3 border p-3 bg-light">
            <h5 class="fw-bold mb-0">Total Pendapatan:</h5>
            <h4 class="fw-bold mb-0">Rp <?= number_format($grand_total, 0, ',', '.'); ?></h4>
        </div>

        <div class="mt-5 text-end no-print">
            <button onclick="window.print()" class="btn btn-primary">Cetak Ulang</button>
            <button onclick="window.close()" class="btn btn-secondary">Tutup</button>
        </div>
    </div>
</body>
</html>