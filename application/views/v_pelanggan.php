<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Menu - Meja <?= $no_meja; ?> | Ayam Geprek Romeesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-danger shadow-sm">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1 fw-bold">🔥 Ayam Geprek Romeesa</span>
            <span class="badge bg-warning text-dark fs-6">Meja <?= $no_meja; ?></span>
        </div>
    </nav>

    <div class="container mt-3 mb-5">
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

        <!-- Daftar Menu -->
        <h5 class="fw-bold mb-3">Silakan Pilih Menu</h5>
        <div class="row">
            <?php foreach($menu as $m): ?>
                <div class="col-6 col-md-4 mb-3">
                    <div class="card h-100 shadow-sm border-0">
                        <?php if(!empty($m['foto'])): ?>
                            <img src="<?= base_url('uploads/' . $m['foto']); ?>" class="card-img-top" style="height: 110px; object-fit: cover;" alt="<?= $m['nama_menu']; ?>">
                        <?php else: ?>
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 110px; font-size: 11px;">Tanpa Foto</div>
                        <?php endif; ?>
                        
                        <div class="card-body p-2 d-flex flex-column justify-content-between">
                            <div>
                                <h6 class="card-title fs-6 fw-bold mb-1"><?= $m['nama_menu']; ?></h6>
                                <p class="text-success fw-bold mb-2" style="font-size: 13px;">Rp <?= number_format($m['harga'], 0, ',', '.'); ?></p>
                            </div>
                            <a href="<?= site_url('pelanggan/add_cart/' . $m['id'] . '/' . $no_meja); ?>" class="btn btn-outline-danger btn-sm w-100">+ Tambah</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Keranjang Pesanan Pelanggan -->
        <div class="card shadow-sm border-0 p-3 mt-4">
            <h5 class="fw-bold mb-3">Keranjang Pesanan Anda</h5>
            <ul class="list-group mb-3">
                <?php 
                $cart = $this->session->userdata('cart_pelanggan');
                $grand_total = 0;
                if(!empty($cart)): 
                    foreach($cart as $item): 
                        $grand_total += $item['subtotal'];
                ?>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="my-0 fw-bold"><?= $item['nama_menu']; ?></h6>
                                <small class="text-muted"><?= $item['qty']; ?> x Rp <?= number_format($item['harga'], 0, ',', '.'); ?></small>
                            </div>
                            <div class="text-end">
                                <span class="text-success fw-bold">Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></span><br>
                                <a href="<?= site_url('pelanggan/remove_cart/' . $item['id'] . '/' . $no_meja); ?>" class="text-danger" style="font-size: 11px; text-decoration: none;">Hapus</a>
                            </div>
                        </div>
                        <!-- Catatan khusus per menu (Opsional) -->
                        <div class="mt-2">
                            <input type="text" name="catatan_<?= $item['id']; ?>" class="form-control form-control-sm" placeholder="Catatan (cth: tidak pedas)">
                        </div>
                    </li>
                <?php endforeach; else: ?>
                    <li class="list-group-item text-center text-muted py-3">Keranjang masih kosong</li>
                <?php endif; ?>
            </ul>

            <div class="d-flex justify-content-between mb-3">
                <h5 class="fw-bold">Total:</h5>
                <h5 class="text-success fw-bold">Rp <?= number_format($grand_total, 0, ',', '.'); ?></h5>
            </div>

            <?php if(!empty($cart)): ?>
                <form action="<?= site_url('pelanggan/checkout/' . $no_meja); ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 13px;">Nama Pemesan:</label>
                        <input type="text" name="nama_pemesan" class="form-control" required placeholder="Masukkan nama Anda">
                    </div>
                    <button type="submit" class="btn btn-danger w-100 fw-bold">Kirim Pesanan ke Kasir</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>