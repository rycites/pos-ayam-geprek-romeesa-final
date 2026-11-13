<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kasir - Ayam Geprek Romeesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Ayam Geprek Romeesa - Kasir</a>
            <div class="d-flex align-items-center">
                <!-- Tombol Pintasan ke Antrean Meja QR -->
                <a href="<?= site_url('cashier/antrean_meja'); ?>" class="btn btn-warning btn-sm me-3 fw-bold">
                    🔔 Antrean Meja QR
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

        <div class="row">
            <!-- Daftar Menu (Kiri) -->
            <div class="col-md-8">
                <div class="card shadow-sm p-3 border-0">
                    <h4 class="mb-3">Daftar Menu</h4>
                    <div class="row">
                        <?php foreach($menu as $m): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 shadow-sm border-0 bg-white">
                                    <!-- Menampilkan Foto Menu -->
                                    <?php if(!empty($m['foto'])): ?>
                                        <img src="<?= base_url('uploads/' . $m['foto']); ?>" alt="<?= $m['nama_menu']; ?>" class="card-img-top" style="height: 130px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 130px; font-size: 12px;">Tanpa Foto</div>
                                    <?php endif; ?>

                                    <div class="card-body d-flex flex-column justify-content-between p-3">
                                        <div>
                                            <span class="badge bg-secondary mb-2" style="font-size: 10px;"><?= ucfirst($m['kategori']); ?></span>
                                            <h5 class="card-title fs-6 fw-bold mb-1"><?= $m['nama_menu']; ?></h5>
                                            <p class="card-text text-success fw-bold mb-2">Rp <?= number_format($m['harga'], 0, ',', '.'); ?></p>
                                        </div>
                                        <a href="<?= site_url('cashier/add_cart/' . $m['id']); ?>" class="btn btn-outline-danger btn-sm w-100">Pilih</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Keranjang Pesanan (Kanan) -->
            <div class="col-md-4">
                <div class="card shadow-sm p-3 border-0">
                    <h4 class="mb-3">Keranjang Pesanan</h4>
                    <hr>
                    <div style="max-height: 200px; overflow-y: auto;">
                        <ul class="list-group mb-3">
                            <?php 
                            $cart = $this->session->userdata('cart');
                            $grand_total = 0;
                            if(!empty($cart)): 
                                foreach($cart as $item): 
                                    $grand_total += $item['subtotal'];
                            ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="my-0 fs-6"><?= $item['nama_menu']; ?></h6>
                                        <small class="text-muted"><?= $item['qty']; ?> x Rp <?= number_format($item['harga'], 0, ',', '.'); ?></small>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-success fw-bold fs-6">Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></span>
                                        <br>
                                        <a href="<?= site_url('cashier/remove_cart/' . $item['id']); ?>" class="text-danger" style="font-size: 11px; text-decoration: none;">Hapus</a>
                                    </div>
                                </li>
                            <?php endforeach; else: ?>
                                <li class="list-group-item text-center text-muted py-4">Keranjang masih kosong</li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <h5>Total:</h5>
                        <h5 class="text-success fw-bold">Rp <?= number_format($grand_total, 0, ',', '.'); ?></h5>
                    </div>

                    <?php if(!empty($cart)): ?>
                        <form action="<?= site_url('cashier/checkout'); ?>" method="POST">
                            <!-- Input Nama Pemesan (Penting untuk Takeaway / Dine-in) -->
                            <div class="mb-2">
                                <label class="form-label fw-bold" style="font-size: 12px;">Nama Pemesan:</label>
                                <input type="text" name="nama_pemesan" class="form-control form-control-sm" required placeholder="Contoh: Budi">
                            </div>

                            <!-- Pilihan Jenis Pesanan Kasir (Takeaway / Dine-in Manual) -->
                            <div class="mb-2">
                                <label class="form-label fw-bold" style="font-size: 12px;">Jenis Pesanan:</label>
                                <select name="jenis_pesanan" id="jenis_pesanan" class="form-select form-select-sm" required onchange="toggleMeja(this.value)">
                                    <option value="Takeaway">Bungkus (Takeaway)</option>
                                    <option value="Dine-in">Makan di Tempat (Dine-in)</option>
                                </select>
                            </div>

                            <!-- Input Nomor Meja (Hanya muncul jika Dine-in dipilih) -->
                            <div class="mb-2" id="box_meja" style="display: none;">
                                <label class="form-label fw-bold" style="font-size: 12px;">Nomor Meja:</label>
                                <input type="number" name="nomor_meja" class="form-control form-control-sm" placeholder="Contoh: 3">
                            </div>

                            <!-- Pilihan Metode Pembayaran -->
                            <div class="mb-2">
                                <label class="form-label fw-bold" style="font-size: 12px;">Metode Pembayaran:</label>
                                <select name="metode_pembayaran" id="metode_pembayaran" class="form-select form-select-sm" onchange="toggleBayar(this.value)">
                                    <option value="Cash">Cash (Tunai)</option>
                                    <option value="QRIS">QRIS / Transfer</option>
                                </select>
                            </div>

                            <!-- Input Uang Bayar (Hanya muncul jika Cash) -->
                            <div class="mb-3" id="box_uang">
                                <label class="form-label fw-bold" style="font-size: 12px;">Uang Pembeli (Rp):</label>
                                <input type="number" name="uang_bayar" class="form-control form-control-sm" placeholder="Contoh: 50000">
                            </div>

                            <button type="submit" class="btn btn-danger w-100">Proses Pembayaran</button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-danger w-100" disabled>Proses Pembayaran</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Script sederhana untuk mengatur kemunculan input meja & cash -->
    <script>
        function toggleMeja(val) {
            let boxMeja = document.getElementById('box_meja');
            if(val === 'Dine-in') {
                boxMeja.style.display = 'block';
            } else {
                boxMeja.style.display = 'none';
            }
        }

        function toggleBayar(val) {
            let boxUang = document.getElementById('box_uang');
            if(val === 'Cash') {
                boxUang.style.display = 'block';
            } else {
                boxUang.style.display = 'none';
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>