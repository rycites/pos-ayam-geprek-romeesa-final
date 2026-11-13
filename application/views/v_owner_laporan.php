<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - Owner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Laporan Penjualan Ayam Geprek Romeesa</h3>
            <a href="<?= site_url('owner'); ?>" class="btn btn-secondary btn-sm">Kembali ke Menu Owner</a>
        </div>

        <!-- Form Filter -->
        <div class="card shadow-sm border-0 p-3 mb-4">
            <form method="GET" action="<?= site_url('owner/laporan'); ?>" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Pilih Kategori:</label>
                    <select name="jenis" id="jenis" class="form-select" onchange="toggleFilter()">
                        <option value="">-- Semua Laporan --</option>
                        <option value="harian" <?= ($this->input->get('jenis') == 'harian') ? 'selected':''; ?>>Harian</option>
                        <option value="mingguan" <?= ($this->input->get('jenis') == 'mingguan') ? 'selected':''; ?>>Mingguan (7 Hari Terakhir)</option>
                        <option value="bulanan" <?= ($this->input->get('jenis') == 'bulanan') ? 'selected':''; ?>>Bulanan</option>
                    </select>
                </div>

                <div class="col-md-3" id="div-tanggal" style="display: none;">
                    <label class="form-label fw-bold">Pilih Tanggal:</label>
                    <input type="date" name="tanggal" value="<?= $this->input->get('tanggal'); ?>" class="form-control">
                </div>

                <div class="col-md-3" id="div-bulan" style="display: none;">
                    <label class="form-label fw-bold">Pilih Bulan:</label>
                    <input type="month" name="bulan" value="<?= $this->input->get('bulan'); ?>" class="form-control">
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-danger w-100">Filter Laporan</button>
                </div>
            </form>
        </div>

        <!-- Tabel Data -->
        <div class="card shadow-sm border-0 p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Hasil Laporan</h5>
                <?php if(!empty($laporan)): ?>
                    <a href="<?= site_url('owner/cetak_laporan?' . $_SERVER['QUERY_STRING']); ?>" target="_blank" class="btn btn-dark btn-sm">🖨️ Cetak / Print</a>
                <?php endif; ?>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-danger">
                        <tr>
                            <th>No</th>
                            <th>No. Faktur</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Total Belanja</th>
                            <th>Uang Bayar</th>
                            <th>Kembalian</th>
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
                                <td><span class="badge bg-secondary"><?= $l['no_faktur']; ?></span></td>
                                <td><?= $l['tanggal']; ?></td>
                                <td><?= $l['nama_kasir'] ? $l['nama_kasir'] : 'Kasir ID: '.$l['id_kasir']; ?></td>
                                <td class="text-success fw-bold">Rp <?= number_format($l['total_bayar'], 0, ',', '.'); ?></td>
                                <td>Rp <?= number_format($l['uang_bayar'], 0, ',', '.'); ?></td>
                                <td>Rp <?= number_format($l['uang_kembalian'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Tidak ada data laporan untuk filter tersebut.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if(!empty($laporan)): ?>
                <div class="alert alert-success mt-3 d-flex justify-content-between align-items-center mb-0">
                    <h5 class="mb-0 fw-bold">Total Pendapatan Keseluruhan:</h5>
                    <h4 class="mb-0 fw-bold">Rp <?= number_format($grand_total, 0, ',', '.'); ?></h4>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleFilter() {
            var jenis = document.getElementById('jenis').value;
            document.getElementById('div-tanggal').style.display = (jenis === 'harian') ? 'block' : 'none';
            document.getElementById('div-bulan').style.display = (jenis === 'bulanan') ? 'block' : 'none';
        }
        window.onload = toggleFilter;
    </script>
</body>
</html>