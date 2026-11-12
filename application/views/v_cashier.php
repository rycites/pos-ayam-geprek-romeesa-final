<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kasir - Ayam Geprek Romeesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar (Diubah dari hijau/success jadi merah/danger) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Ayam Geprek Romeesa - Kasir</a>
            <div class="d-flex">
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
                <div class="card shadow-sm p-3">
                    <h4 class="mb-3">Daftar Menu</h4>
                    <div class="row">
                        <?php foreach($menu as $m): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 shadow-sm border-0 bg-white">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div>
                                            <span class="badge bg-secondary mb-2" style="font-size: 10px;"><?= ucfirst($m['kategori']); ?></span>
                                            <h5 class="card-title fs-6 fw-bold"><?= $m['nama_menu']; ?></h5>
                                            <p class="card-text text-success fw-bold">Rp <?= number_format($m['harga'], 0, ',', '.'); ?></p>
                                        </div>
                                        <a href="<?= site_url('cashier/add_cart/' . $m['id']); ?>" class="btn btn-outline-danger btn-sm w-100 mt-2">Pilih</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Keranjang Pesanan (Kanan) -->
            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <h4 class="mb-3">Keranjang Pesanan</h4>
                    <hr>
                    
                    <div style="max-height: 250px; overflow-y: auto;">
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
                            <div class="mb-3">
                                <label class="form-label fw-bold" style="font-size: 13px;">Uang Pembeli (Rp):</label>
                                <input type="number" name="uang_bayar" class="form-control" required placeholder="Contoh: 50000">
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>