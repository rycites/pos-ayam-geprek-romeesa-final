<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? $title : 'Laporan Penjualan - Owner'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container mt-4 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><?= isset($page_title) ? $page_title : 'Laporan Keuangan & Transaksi'; ?></h3>
            <a href="<?= site_url('auth/logout'); ?>" class="btn btn-outline-danger btn-sm">Logout</a>
        </div>

        <!-- Form Filter Lengkap (Harian, Mingguan, Bulanan & Metode Pembayaran) -->
        <div class="card shadow-sm border-0 p-3 mb-4">
            <form method="GET" action="<?= site_url('owner/laporan'); ?>" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold small">Pilih Kategori Waktu:</label>
                    <select name="jenis" id="jenis" class="form-select form-select-sm" onchange="toggleFilter()">
                        <option value="">-- Rentang Tanggal Default --</option>
                        <option value="harian" <?= ($this->input->get('jenis') == 'harian') ? 'selected':''; ?>>Harian (Pilih Tanggal)</option>
                        <option value="mingguan" <?= ($this->input->get('jenis') == 'mingguan') ? 'selected':''; ?>>Mingguan (7 Hari Terakhir)</option>
                        <option value="bulanan" <?= ($this->input->get('jenis') == 'bulanan') ? 'selected':''; ?>>Bulanan (Pilih Bulan)</option>
                    </select>
                </div>

                <!-- Input Tanggal untuk Harian -->
                <div class="col-md-2" id="div-tanggal" style="display: none;">
                    <label class="form-label fw-bold small">Pilih Tanggal:</label>
                    <input type="date" name="tanggal" value="<?= $this->input->get('tanggal'); ?>" class="form-control form-control-sm">
                </div>

                <!-- Input Bulan untuk Bulanan -->
                <div class="col-md-2" id="div-bulan" style="display: none;">
                    <label class="form-label fw-bold small">Pilih Bulan:</label>
                    <input type="month" name="bulan" value="<?= $this->input->get('bulan'); ?>" class="form-control form-control-sm">
                </div>

                <!-- Filter Metode Pembayaran (QRIS / Cash) -->
                <div class="col-md-3">
                    <label class="form-label fw-bold small">Metode Pembayaran:</label>
                    <select name="metode" class="form-select form-select-sm">
                        <option value="">-- Semua Metode --</option>
                        <option value="QRIS" <?= ($this->input->get('metode') == 'QRIS') ? 'selected':''; ?>>QRIS (Self-Order)</option>
                        <option value="Cash" <?= ($this->input->get('metode') == 'Cash') ? 'selected':''; ?>>Cash (Tunai)</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-danger btn-sm w-100 fw-bold">Terapkan Filter</button>
                </div>
            </form>
        </div>

        <!-- Tabel Hasil Laporan -->
        <div class="card shadow-sm border-0 p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Hasil Data Transaksi</h5>
                <?php if(!empty($transaksi)): ?>
                    <a href="<?= site_url('owner/cetak_laporan?' . $_SERVER['QUERY_STRING']); ?>" target="_blank" class="btn btn-dark btn-sm">🖨️ Cetak / Print</a>
                <?php endif; ?>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle small">
                    <thead class="table-danger">
                        <tr>
                            <th>No</th>
                            <th>No. Faktur</th>
                            <th>Tanggal & Waktu</th>
                            <th>Metode</th>
                            <th>Kasir / Sumber</th>
                            <th>Total Belanja</th>
                            <th>Uang Bayar</th>
                            <th>Kembalian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $grand_total = 0;
                        if(!empty($transaksi)): 
                            $no = 1;
                            foreach($transaksi as $t):
                                $grand_total += $t['total_bayar']; 
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><span class="badge bg-secondary"><?= $t['no_faktur']; ?></span></td>
                                <td><?= $t['tanggal']; ?></td>
                                <td>
                                    <?php if(isset($t['metode_pembayaran']) && strtolower($t['metode_pembayaran']) == 'qris'): ?>
                                        <span class="badge bg-success"><i class="fa-solid fa-qrcode me-1"></i> QRIS</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-money-bill me-1"></i> Cash</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= isset($t['nama_kasir']) && $t['nama_kasir'] ? $t['nama_kasir'] : ($t['id_kasir'] == 0 ? 'Mandiri QR' : 'Kasir ID: '.$t['id_kasir']); ?></td>
                                <td class="text-success fw-bold">Rp <?= number_format($t['total_bayar'], 0, ',', '.'); ?></td>
                                <td>Rp <?= number_format($t['uang_bayar'], 0, ',', '.'); ?></td>
                                <td>Rp <?= number_format($t['uang_kembalian'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Tidak ada data transaksi untuk filter tersebut.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if(!empty($transaksi)): ?>
                <div class="alert alert-success mt-3 d-flex justify-content-between align-items-center mb-0">
                    <h5 class="mb-0 fw-bold">Total Pendapatan (Omset):</h5>
                    <h4 class="mb-0 fw-bold">Rp <?= number_format($grand_total, 0, ',', '.'); ?></h4>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Script JavaScript untuk memunculkan input tanggal/bulan secara dinamis -->
    <script>
        function toggleFilter() {
            var jenis = document.getElementById('jenis').value;
            document.getElementById('div-tanggal').style.display = (jenis === 'harian') ? 'block' : 'none';
            document.getElementById('div-bulan').style.display = (jenis === 'bulanan') ? 'block' : 'none';
        }
        // Jalankan saat halaman dimuat agar kondisi form sesuai dengan pilihan terakhir
        window.onload = toggleFilter;
    </script>
</body>
</html>