<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Antrean Meja QR - Kasir Ayam Geprek Romeesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fa-solid fa-qrcode me-2"></i> Antrean Pesanan Meja (QR Code)
            </a>
            <div class="d-flex align-items-center">
                <a href="<?= site_url('cashier'); ?>" class="btn btn-outline-light btn-sm me-3 fw-bold">
                    <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Kasir Utama
                </a>
                <span class="navbar-text text-white me-3">
                    Halo, <b><?= $this->session->userdata('nama'); ?></b>
                </span>
                <a href="<?= site_url('auth/logout'); ?>" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mt-4">
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <h3 class="mb-1 fw-bold text-dark">Daftar Pelanggan Meja (Menunggu Pembayaran)</h3>
        <p class="text-muted mb-4">Pelanggan yang memesan mandiri via QR Code di meja akan muncul di sini beserta nama pemesannya. Silakan konfirmasi pembayaran agar pesanan otomatis diteruskan ke dapur.</p>

        <div class="card shadow-sm border-0 p-3 rounded-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No Faktur</th>
                            <th>Waktu Pesan</th>
                            <th>Meja & Nama Pemesan</th>
                            <th>Total Tagihan</th>
                            <th class="text-center" style="width: 32%;">Aksi Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($pesanan_pending)): ?>
                            <?php foreach($pesanan_pending as $p): ?>
                                <tr>
                                    <td><span class="badge bg-secondary px-2 py-1"><?= $p['no_faktur']; ?></span></td>
                                    <td class="text-muted small"><i class="fa-regular fa-clock me-1"></i><?= date('H:i:s', strtotime($p['tanggal'])); ?> WIB</td>
                                    <td>
                                        <div class="fw-bold text-danger fs-6">
                                            <i class="fa-solid fa-user-tag me-1"></i> <?= $p['nomor_meja']; ?>
                                        </div>
                                    </td>
                                    <td class="text-success fw-bold fs-6">Rp <?= number_format($p['total_bayar'], 0, ',', '.'); ?></td>
                                    <td>
                                        <!-- Form Konfirmasi Pembayaran oleh Kasir -->
                                        <form action="<?= site_url('cashier/bayar_pesanan_meja/' . $p['id_transaksi']); ?>" method="POST" class="row g-2">
                                            <div class="col-7">
                                                <select name="metode_pembayaran" class="form-select form-select-sm" onchange="toggleUangModal(this, <?= $p['id_transaksi']; ?>)">
                                                    <option value="Cash">Cash (Tunai)</option>
                                                    <option value="QRIS">QRIS / Transfer</option>
                                                </select>
                                                <input type="number" name="uang_bayar" id="input_uang_<?= $p['id_transaksi']; ?>" class="form-control form-control-sm mt-1" placeholder="Nominal uang bayar...">
                                            </div>
                                            <div class="col-5">
                                                <button type="submit" class="btn btn-success btn-sm w-100 h-100 fw-bold shadow-sm">
                                                    <i class="fa-solid fa-check me-1"></i> Konfirmasi Lunas
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-mug-hot fa-2x mb-2 text-secondary opacity-50"></i>
                                    <p class="mb-0">Belum ada antrean pesanan meja yang menunggu pembayaran.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script kecil untuk menyembunyikan input uang jika QRIS dipilih -->
    <script>
        function toggleUangModal(selectObj, idTransaksi) {
            let inputUang = document.getElementById('input_uang_' + idTransaksi);
            if(selectObj.value === 'QRIS') {
                inputUang.style.display = 'none';
                inputUang.required = false;
                inputUang.value = '';
            } else {
                inputUang.style.display = 'block';
                inputUang.required = true;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>