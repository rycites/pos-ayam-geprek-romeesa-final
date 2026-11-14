<!-- KARTU STATISTIK RINGKASAN -->
<div class="row g-4 mb-4">
    <!-- Total Pendapatan -->
    <div class="col-xl-6 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white position-relative overflow-hidden">
            <div class="position-absolute top-0 start-0 bottom-0 bg-danger" style="width: 5px;"></div>
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Pendapatan Bersih</span>
                    <h2 class="fw-extrabold text-dark mb-0 mt-2 display-6">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></h2>
                    <span class="badge bg-success bg-opacity-10 text-success mt-2 px-2 py-1 rounded-pill small fw-medium">
                        <i class="fa-solid fa-arrow-trend-up me-1"></i> Akumulasi Real-Time
                    </span>
                </div>
                <div class="bg-danger bg-opacity-10 text-danger rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="fa-solid fa-wallet fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Transaksi -->
    <div class="col-xl-6 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white position-relative overflow-hidden">
            <div class="position-absolute top-0 start-0 bottom-0 bg-dark" style="width: 5px;"></div>
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Transaksi Selesai</span>
                    <h2 class="fw-extrabold text-dark mb-0 mt-2 display-6"><?= number_format($total_transaksi, 0, ',', '.'); ?> <span class="fs-5 fw-normal text-muted">Pesanan</span></h2>
                    <span class="badge bg-dark bg-opacity-10 text-dark mt-2 px-2 py-1 rounded-pill small fw-medium">
                        <i class="fa-solid fa-circle-check me-1"></i> Berhasil Diproses
                    </span>
                </div>
                <div class="bg-dark bg-opacity-10 text-dark rounded-4 p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="fa-solid fa-receipt fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TABEL RIWAYAT TRANSAKSI TERBARU -->
<div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
    <div class="card-header bg-white py-4 px-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center border-bottom border-light gap-3">
        <div>
            <h5 class="fw-bold text-dark mb-1 fs-5">
                <i class="fa-solid fa-clock-rotate-left text-danger me-2"></i> Riwayat Transaksi Terbaru
            </h5>
            <p class="text-muted small mb-0">Daftar transaksi penjualan langsung yang masuk ke sistem kasir.</p>
        </div>
        <a href="<?= site_url('owner/laporan'); ?>" class="btn btn-dark btn-sm px-4 py-2 rounded-pill fw-semibold shadow-sm d-inline-flex align-items-center justify-content-center">
            <span>Lihat Semua Laporan</span>
            <i class="fa-solid fa-arrow-right-long ms-2"></i>
        </a>
    </div>
    
    <div class="card-body px-0 py-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">
                <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                    <tr>
                        <th class="py-3 px-4 fw-bold">ID Transaksi</th>
                        <th class="py-3 fw-bold">Kasir Bertugas</th>
                        <th class="py-3 fw-bold">Tanggal & Waktu</th>
                        <th class="py-3 fw-bold text-end">Total Bayar</th>
                        <th class="py-3 fw-bold text-center px-4">Status Pesanan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($laporan)): ?>
                        <?php foreach ($laporan as $row): ?>
                            <?php 
                                // Tampilkan konsisten sebagai "Kasir"
                                $nama_kasir = 'Kasir';
                                $inisial = 'K';
                            ?>
                            <tr>
                                <td class="px-4 py-3 fw-bold text-dark">
                                    <span class="text-danger">#</span>TRX-<?= $row['id_transaksi']; ?>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light border rounded-circle d-flex align-items-center justify-content-center me-2 text-secondary fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            <?= $inisial; ?>
                                        </div>
                                        <span class="fw-medium text-dark"><?= $nama_kasir; ?></span>
                                    </div>
                                </td>
                                <td class="py-3 text-muted small">
                                    <i class="fa-regular fa-calendar-days me-1 text-secondary"></i> <?= date('d M Y, H:i', strtotime($row['tanggal'])); ?> WIB
                                </td>
                                <td class="py-3 text-end fw-bold text-dark">
                                    Rp <?= number_format($row['total_bayar'], 0, ',', '.'); ?>
                                </td>
                                <td class="py-3 text-center px-4">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1.5 rounded-pill small fw-medium">
                                        <i class="fa-solid fa-check me-1"></i> Selesai
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <i class="fa-solid fa-folder-open fa-3x text-secondary opacity-25 mb-3"></i>
                                    <p class="mb-0 fw-medium text-secondary">Belum ada data transaksi yang tercatat di sistem.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>