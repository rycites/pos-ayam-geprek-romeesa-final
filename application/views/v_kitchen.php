<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Dapur - Ayam Geprek Romeesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta http-equiv="refresh" content="10">
</head>
<body class="bg-dark text-white">
    <!-- Navbar (Sudah Merah) -->
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
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-warning text-dark"><?= $p['no_faktur']; ?></span>
                                        <small class="text-light"><?= date('H:i:s', strtotime($p['tanggal'])); ?></small>
                                    </div>
                                    <h4 class="card-title fw-bold text-warning"><?= $p['nama_menu']; ?></h4>
                                    <p class="card-text fs-5">Jumlah: <span class="badge bg-danger"><?= $p['jumlah']; ?> Porsi</span></p>
                                    <p class="card-text mb-2">
                                        <strong>Catatan Khusus:</strong><br>
                                        <span class="text-info"><?= $p['catatan_khusus'] ? $p['catatan_khusus'] : '-'; ?></span>
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