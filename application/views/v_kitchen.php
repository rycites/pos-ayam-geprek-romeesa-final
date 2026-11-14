<div class="container-fluid px-4">
    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-header bg-white py-3 px-4 border-bottom border-light">
            <h5 class="fw-bold text-dark mb-1 fs-6">
                <i class="fa-solid fa-fire-burner text-danger me-2"></i> Pesanan Yang Harus Dimasak
            </h5>
            <p class="text-muted small mb-0" style="font-size: 13px;">Daftar pesanan aktif dari kasir dan meja QR yang perlu segera disiapkan.</p>
        </div>

        <div class="card-body p-4">
            <div class="row g-3">
                <?php if (!empty($pesanan)): ?>
                    <?php foreach ($pesanan as $p): ?>
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="card border border-light shadow-sm rounded-4 h-100 overflow-hidden bg-white">
                                <div class="card-body p-3 d-flex flex-column justify-content-between">
                                    <div>
                                        <!-- Header Kartu: No Faktur & Meja -->
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 12px;">
                                                #<?= isset($p['no_faktur']) ? $p['no_faktur'] : '-'; ?>
                                            </span>
                                            <span class="badge bg-warning bg-opacity-10 text-dark px-2.5 py-1.5 rounded-pill fw-semibold border border-warning border-opacity-25 text-truncate" style="max-width: 55%; font-size: 12px;">
                                                <i class="fa-solid fa-chair me-1 text-danger"></i> <?= isset($p['nomor_meja']) ? $p['nomor_meja'] : '-'; ?>
                                            </span>
                                        </div>

                                        <!-- Detail Menu & Foto -->
                                        <div class="d-flex align-items-center mb-3">
                                            <?php if (!empty($p['foto'])): ?>
                                                <img src="<?= base_url('uploads/' . $p['foto']); ?>" alt="Menu" class="rounded-3 me-3 shadow-sm" style="width: 55px; height: 55px; object-fit: cover; flex-shrink: 0;">
                                            <?php else: ?>
                                                <div class="bg-light rounded-3 me-3 d-flex align-items-center justify-content-center text-secondary" style="width: 55px; height: 55px; flex-shrink: 0;">
                                                    <i class="fa-solid fa-utensils"></i>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 14px;" title="<?= isset($p['nama_menu']) ? $p['nama_menu'] : ''; ?>">
                                                    <?= isset($p['nama_menu']) ? $p['nama_menu'] : 'Menu Tidak Ditemukan'; ?>
                                                </h6>
                                                <span class="text-muted" style="font-size: 13px;">
                                                    Jumlah: <strong class="text-danger"><?= isset($p['qty']) ? $p['qty'] : (isset($p['jumlah']) ? $p['jumlah'] : '1'); ?> Porsi</strong>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Waktu Pesan -->
                                        <p class="text-muted mb-3" style="font-size: 12px;">
                                            <i class="fa-regular fa-clock me-1 text-secondary"></i> Waktu: <?= isset($p['tanggal']) ? date('H:i', strtotime($p['tanggal'])) : '-'; ?> WIB
                                        </p>
                                    </div>

                                    <!-- Tombol Selesai -->
                                    <a href="<?= site_url('kitchen/selesai/' . (isset($p['id_detail']) ? $p['id_detail'] : '')); ?>" class="btn btn-dark w-100 rounded-pill py-2 fw-semibold shadow-sm d-flex align-items-center justify-content-center" style="font-size: 13px;">
                                        <i class="fa-solid fa-circle-check me-2 text-success"></i> Tandai Selesai / Siap Saji
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5 text-muted">
                        <div class="py-5">
                            <i class="fa-solid fa-mug-hot fa-3x text-secondary opacity-25 mb-3"></i>
                            <p class="mb-0 fw-medium text-secondary" style="font-size: 14px;">Belum ada pesanan aktif di dapur saat ini. Semua pesanan sudah selesai!</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>