<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Owner Dashboard - Ayam Geprek Romeesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Owner Dashboard - Ayam Geprek Romeesa</a>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">
                    Halo, Owner <b><?= $this->session->userdata('nama'); ?></b>
                </span>
                <a href="<?= site_url('auth/logout'); ?>" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mt-4 mb-5">
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm bg-success text-white p-3">
                    <h6 class="text-uppercase fw-semibold">Total Pendapatan</h6>
                    <h3 class="fw-bold mb-0">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></h3>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm bg-primary text-white p-3">
                    <h6 class="text-uppercase fw-semibold">Total Transaksi Selesai</h6>
                    <h3 class="fw-bold mb-0"><?= $total_transaksi; ?> Transaksi</h3>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Riwayat Transaksi Penjualan</h4>
                <div>
                    <!-- Tombol Menuju Halaman Filter & Cetak Laporan -->
                    <a href="<?= site_url('owner/laporan'); ?>" class="btn btn-dark btn-sm me-2">📊 Filter & Cetak Laporan</a>
                    <!-- Tombol Kelola Menu -->
                    <a href="<?= site_url('owner/menu'); ?>" class="btn btn-danger btn-sm">Kelola Menu Makanan</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>No Faktur</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Total Bayar</th>
                            <th>Uang Bayar</th>
                            <th>Kembalian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($laporan)): ?>
                            <?php $no = 1; foreach($laporan as $row): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><span class="badge bg-secondary"><?= $row['no_faktur']; ?></span></td>
                                    <td><?= $row['tanggal']; ?></td>
                                    <td><?= $row['nama_kasir'] ? $row['nama_kasir'] : 'Kasir ID: '.$row['id_kasir']; ?></td>
                                    <td class="fw-bold text-success">Rp <?= number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                                    <td>Rp <?= number_format($row['uang_bayar'], 0, ',', '.'); ?></td>
                                    <td>Rp <?= number_format($row['uang_kembalian'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data transaksi penjualan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>