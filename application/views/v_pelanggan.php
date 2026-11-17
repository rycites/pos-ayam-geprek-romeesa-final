<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Menu - Meja <?= $no_meja; ?> | Ayam Geprek Romeesa</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 110px; /* Diperbesar sedikit agar tidak tertutup floating bar status */
        }
        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .card {
            border-radius: 1rem;
        }
        .floating-cart {
            position: fixed;
            bottom: 65px; /* Dinaikkan sedikit agar berdampingan dengan bar status */
            left: 0;
            right: 0;
            background: #ffffff;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
            z-index: 1029;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            padding: 12px 20px;
        }
        .floating-status-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #212529;
            z-index: 1030;
            padding: 10px 20px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>

    <!-- Header / Navbar Pelanggan -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm sticky-top py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <span class="navbar-brand text-white fs-5 mb-0">
                <i class="fa-solid fa-utensils me-2"></i> Ayam Geprek Romeesa
            </span>
            <span class="badge bg-white text-danger px-3 py-2 rounded-pill fw-bold shadow-sm fs-6">
                <i class="fa-solid fa-chair me-1"></i> Meja <?= $no_meja; ?>
            </span>
        </div>
    </nav>

    <div class="container my-4">
        <!-- Flash Message -->
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-4 mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> <?= $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-4 mb-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- DAFTAR MENU -->
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 p-4 bg-white mb-4">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="fa-solid fa-book-open text-danger me-2"></i> Pilih Menu Favorit Anda
                    </h5>
                    <p class="text-muted small mb-4">Silakan pilih menu yang diinginkan untuk dimasukkan ke dalam keranjang pesanan.</p>

                    <div class="row g-3">
                        <?php if(!empty($menu)): ?>
                            <?php foreach($menu as $m): ?>
                                <div class="col-6 col-md-4">
                                    <div class="card h-100 shadow-sm border border-light rounded-4 bg-white overflow-hidden">
                                        <?php if(!empty($m['foto'])): ?>
                                            <img src="<?= base_url('uploads/' . $m['foto']); ?>" alt="<?= $m['nama_menu']; ?>" class="card-img-top" style="height: 110px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light text-secondary d-flex align-items-center justify-content-center" style="height: 110px; font-size: 12px;">
                                                <i class="fa-solid fa-image fa-2x opacity-50"></i>
                                            </div>
                                        <?php endif; ?>

                                        <div class="card-body d-flex flex-column justify-content-between p-3">
                                            <div>
                                                <span class="badge bg-danger bg-opacity-10 text-danger mb-2 px-2 py-1 rounded-pill" style="font-size: 10px;"><?= ucfirst($m['kategori'] ?? 'Menu'); ?></span>
                                                <h6 class="card-title fw-bold text-dark mb-1 text-truncate" title="<?= $m['nama_menu']; ?>"><?= $m['nama_menu']; ?></h6>
                                                <p class="card-text text-success fw-bold mb-3" style="font-size: 14px;">Rp <?= number_format($m['harga'], 0, ',', '.'); ?></p>
                                            </div>
                                            <a href="<?= site_url('pelanggan/add_cart/' . $m['id'] . '/' . $no_meja); ?>" class="btn btn-outline-danger btn-sm w-100 rounded-pill fw-semibold">
                                                <i class="fa-solid fa-plus me-1"></i> Tambah
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12 text-center py-5 text-muted">
                                <i class="fa-solid fa-box-open fa-3x opacity-25 mb-3"></i>
                                <p class="mb-0">Belum ada menu yang tersedia saat ini.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- KERANJANG & CHECKOUT (DESKTOP VIEW) -->
            <div class="col-lg-5 d-none d-lg-block">
                <div class="card shadow-sm border-0 p-4 bg-white sticky-top" style="top: 90px;">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="fa-solid fa-cart-shopping text-danger me-2"></i> Keranjang Meja <?= $no_meja; ?>
                    </h5>
                    <hr class="text-secondary opacity-25">
                    
                    <div style="max-height: 220px; overflow-y: auto;" class="pe-1">
                        <ul class="list-group list-group-flush mb-3">
                            <?php 
                            $cart = $this->session->userdata('cart_pelanggan');
                            $grand_total = 0;
                            $total_qty = 0;
                            if(!empty($cart)): 
                                foreach($cart as $item): 
                                    $grand_total += $item['subtotal'];
                                    $total_qty += $item['qty'];
                            ?>
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent">
                                    <div>
                                        <h6 class="my-0 fs-6 fw-bold text-dark"><?= $item['nama_menu']; ?></h6>
                                        <small class="text-muted"><?= $item['qty']; ?>x @ Rp <?= number_format($item['harga'], 0, ',', '.'); ?></small>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-success fw-bold fs-6">Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></span>
                                        <br>
                                        <a href="<?= site_url('pelanggan/remove_cart/' . $item['id'] . '/' . $no_meja); ?>" class="text-danger small text-decoration-none fw-semibold" style="font-size: 11px;">
                                            <i class="fa-solid fa-trash-can me-1"></i> Hapus
                                        </a>
                                    </div>
                                </li>
                            <?php endforeach; else: ?>
                                <div class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-basket-shopping fa-2x opacity-25 mb-2"></i>
                                    <p class="mb-0 small">Keranjang masih kosong</p>
                                </div>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <hr class="text-secondary opacity-25">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-secondary">Total Pembayaran:</span>
                        <h4 class="text-success fw-extrabold mb-0">Rp <?= number_format($grand_total, 0, ',', '.'); ?></h4>
                    </div>

                    <?php if(!empty($cart)): ?>
                        <form action="<?= site_url('pelanggan/checkout/' . $no_meja); ?>" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-secondary">Nama Anda:</label>
                                <input type="text" name="nama_pemesan" class="form-control rounded-3 py-2" required placeholder="Contoh: Andi">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-secondary">Metode Pembayaran:</label>
                                <select name="metode_pembayaran" id="metode_pembayaran_desktop" class="form-select rounded-3 py-2" onchange="toggleQris(this.value, 'desktop')">
                                    <option value="Cash">Cash (Bayar ke Kasir)</option>
                                    <option value="QRIS">QRIS / Transfer (Langsung Proses)</option>
                                </select>
                            </div>

                            <!-- Box QRIS Otomatis (Desktop) -->
                            <div class="mb-4 text-center p-3 bg-light rounded-4 border" id="box_qris_desktop" style="display: none;">
                                <p class="fw-bold text-dark small mb-2"><i class="fa-solid fa-qrcode text-danger me-1"></i> Scan QRIS di Bawah Ini:</p>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=AYAM-GEPREK-ROMEESA-MEJA-<?= $no_meja; ?>" alt="QRIS Code" class="img-fluid rounded-3 mb-2 bg-white p-2 border" style="width: 130px; height: 130px;">
                                <small class="text-muted d-block" style="font-size: 11px;">Silakan scan pakai GoPay, OVO, Dana, atau m-Banking Anda.</small>
                            </div>

                            <button type="submit" class="btn btn-danger w-100 rounded-pill py-2.5 fw-bold shadow-sm">
                                <i class="fa-solid fa-paper-plane me-2"></i> Kirim Pesanan Sekarang
                            </button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-secondary w-100 rounded-pill py-2.5 fw-bold opacity-50" disabled>
                            Kirim Pesanan Sekarang
                        </button>
                    <?php endif; ?>

                    <!-- Tombol Cek Status Aktif (Desktop Sidebar) -->
                    <div class="mt-4 pt-3 border-top text-center">
                        <a href="<?= site_url('pelanggan/cek_status_aktif/' . $no_meja); ?>" class="btn btn-outline-dark btn-sm rounded-pill w-100 py-2 fw-semibold">
                            <i class="fa-solid fa-clock-rotate-left me-1 text-warning"></i> Cek Status Pesanan Meja Ini
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FLOATING CART / KERANJANG MENGAMBANG KHUSUS MOBILE -->
    <?php 
    $cart = $this->session->userdata('cart_pelanggan');
    $grand_total = 0;
    $total_qty = 0;
    if(!empty($cart)) {
        foreach($cart as $item) {
            $grand_total += $item['subtotal'];
            $total_qty += $item['qty'];
        }
    }
    ?>
    <?php if(!empty($cart)): ?>
        <div class="floating-cart d-lg-none">
            <div class="container d-flex align-items-center justify-content-between">
                <div>
                    <span class="badge bg-danger rounded-pill px-2 py-1"><?= $total_qty; ?> Item</span>
                    <h5 class="fw-bold text-dark mb-0 fs-6">Rp <?= number_format($grand_total, 0, ',', '.'); ?></h5>
                </div>
                <button type="button" class="btn btn-danger rounded-pill px-4 py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCheckout">
                    <i class="fa-solid fa-cart-shopping me-2"></i> Lihat Keranjang & Checkout
                </button>
            </div>
        </div>

        <!-- MODAL CHECKOUT MOBILE -->
        <div class="modal fade" id="modalCheckout" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content rounded-4 border-0 p-3">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-dark">
                            <i class="fa-solid fa-cart-shopping text-danger me-2"></i> Keranjang Pesanan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group list-group-flush mb-3">
                            <?php foreach($cart as $item): ?>
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent">
                                    <div>
                                        <h6 class="my-0 fs-6 fw-bold text-dark"><?= $item['nama_menu']; ?></h6>
                                        <small class="text-muted"><?= $item['qty']; ?>x @ Rp <?= number_format($item['harga'], 0, ',', '.'); ?></small>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-success fw-bold fs-6">Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></span>
                                        <br>
                                        <a href="<?= site_url('pelanggan/remove_cart/' . $item['id'] . '/' . $no_meja); ?>" class="text-danger small text-decoration-none fw-semibold">Hapus</a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <hr class="text-secondary opacity-25">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-secondary">Total Pembayaran:</span>
                            <h4 class="text-success fw-extrabold mb-0">Rp <?= number_format($grand_total, 0, ',', '.'); ?></h4>
                        </div>

                        <form action="<?= site_url('pelanggan/checkout/' . $no_meja); ?>" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-secondary">Nama Anda:</label>
                                <input type="text" name="nama_pemesan" class="form-control rounded-3 py-2" required placeholder="Contoh: Andi">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-secondary">Metode Pembayaran:</label>
                                <select name="metode_pembayaran" id="metode_pembayaran_mobile" class="form-select rounded-3 py-2" onchange="toggleQris(this.value, 'mobile')">
                                    <option value="Cash">Cash (Bayar ke Kasir)</option>
                                    <option value="QRIS">QRIS / Transfer (Langsung Proses)</option>
                                </select>
                            </div>

                            <!-- Box QRIS Otomatis (Mobile Modal) -->
                            <div class="mb-4 text-center p-3 bg-light rounded-4 border" id="box_qris_mobile" style="display: none;">
                                <p class="fw-bold text-dark small mb-2"><i class="fa-solid fa-qrcode text-danger me-1"></i> Scan QRIS di Bawah Ini:</p>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=AYAM-GEPREK-ROMEESA-MEJA-<?= $no_meja; ?>" alt="QRIS Code" class="img-fluid rounded-3 mb-2 bg-white p-2 border" style="width: 130px; height: 130px;">
                                <small class="text-muted d-block" style="font-size: 11px;">Silakan scan pakai GoPay, OVO, Dana, atau m-Banking Anda.</small>
                            </div>
                            
                            <button type="submit" class="btn btn-danger w-100 rounded-pill py-2.5 fw-bold shadow-sm">
                                <i class="fa-solid fa-paper-plane me-2"></i> Kirim Pesanan Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- FLOATING STATUS BAR (KHUSUS MOBILE - AKSES STATUS PESANAN AKTIF) -->
    <div class="floating-status-bar d-lg-none">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="text-white">
                <small class="d-block text-white-50" style="font-size: 0.65rem;">RIWAYAT MEJA <?= $no_meja; ?></small>
                <span class="fw-bold small"><i class="fa-solid fa-clock-rotate-left text-warning me-1"></i> Cek Status Pesanan</span>
            </div>
            <a href="<?= site_url('pelanggan/cek_status_aktif/' . $no_meja); ?>" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-bold">
                Buka Status <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    <!-- Script Interaktif untuk QRIS -->
    <script>
        function toggleQris(val, type) {
            let boxQris = document.getElementById('box_qris_' + type);
            if(val === 'QRIS') {
                boxQris.style.display = 'block';
            } else {
                boxQris.style.display = 'none';
            }
        }
    </script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>