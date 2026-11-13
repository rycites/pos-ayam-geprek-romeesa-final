<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Dapur - Ayam Geprek Romeesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta http-equiv="refresh" content="10">
</head>
<body class="bg-dark text-white">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Kitchen Dashboard - Ayam Geprek Romeesa</a>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">
                    Halo, Chef <b><?= $this->session->userdata('nama'); ?></b> (Auto-refresh 10s)
                </span>
                <a href="<?= site_url('auth/logout'); ?>" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mt-4">
        <h3 class="mb-4">Daftar Pesanan Masuk (Live Kitchen)</h3>
        
        <div class="row">
            <?php if(!empty($pesanan)): ?>
                <?php foreach($pesanan as $p): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-secondary text-white border-0 shadow-sm h-100">
                            <!-- Menampilkan Foto Menu di Dapur -->
                            <?php if(!empty($p['foto'])): ?>
                                <img src="<?= base_url('uploads/' . $p['foto']); ?>" alt="<?= $p['nama_menu']; ?>" class="card-img-top" style="height: 140px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-dark text-light d-flex align-items-center justify-content-center" style="height: 140px; font-size: 12px;">Tanpa Foto</div>
                            <?php endif; ?>

                            <div class="card-body d-flex flex-column justify-content-between p-3">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-warning text-dark"><?= $p['no_faktur']; ?></span>
                                        <small class="text-light"><?= date('H:i:s', strtotime($p['tanggal'])); ?></small>
                                    </div>
                                    
                                    <!-- Menampilkan Nomor Meja & Nama Pemesan -->
                                    <?php if(!empty($p['nomor_meja'])): ?>
                                        <div class="alert alert-danger py-1 px-2 mb-2 text-center fw-bold" style="font-size: 13px;">
                                            📍 <?= $p['nomor_meja']; ?>
                                        </div>
                                    <?php endif; ?>

                                    <h4 class="card-title fs-5 fw-bold text-warning mb-2"><?= $p['nama_menu']; ?></h4>
                                    <p class="card-text fs-6 mb-2">Jumlah: <span class="badge bg-danger"><?= $p['jumlah']; ?> Porsi</span></p>
                                    <p class="card-text mb-2" style="font-size: 13px;">
                                        <strong>Catatan Khusus:</strong><br>
                                        <span class="text-info"><?= !empty($p['catatan_khusus']) ? $p['catatan_khusus'] : '-'; ?></span>
                                    </p>
                                </div>
                                <a href="<?= site_url('kitchen/selesai/' . $p['id_detail']); ?>" class="btn btn-success w-100 mt-3 btn-sm">Selesai / Hidangkan</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-5">
                    <h4>Belum ada pesanan aktif di dapur. Kerja bagus!</h4>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>