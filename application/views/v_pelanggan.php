<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Menu - Meja <?= $no_meja; ?> | Ayam Geprek Romeesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome untuk ikon keranjang -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light" style="padding-bottom: 80px;"> <!-- Padding bawah agar konten tidak tertutup floating bar -->
    
    <!-- Navbar Atas -->
    <nav class="navbar navbar-dark bg-danger shadow-sm sticky-top">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1 fw-bold fs-5">🔥 Ayam Geprek Romeesa</span>
            <span class="badge bg-warning text-dark fs-6">Meja <?= $no_meja; ?></span>
        </div>
    </nav>

    <div class="container mt-3 mb-4">
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
        <h5 class="fw-bold mb-3">Daftar Menu</h5>
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
                            <a href="<?= site_url('pelanggan/add_cart/' . $m['id'] . '/' . $no_meja); ?>" class="btn btn-outline-danger btn-sm w-100 fw-bold">+ Tambah</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ================= FLOATING BOTTOM BAR (ALA GACOAN) ================= -->
    <?php 
    $cart = $this->session->userdata('cart_pelanggan');
    $total_qty = 0;
    $grand_total = 0;
    if(!empty($cart)) {
        foreach($cart as $item) {
            $total_qty += $item['qty'];
            $grand_total += $item['subtotal'];
        }
    }
    ?>

    <div class="fixed-bottom bg-white border-top shadow-lg p-3">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <span class="text-muted small d-block">Total Pesanan</span>
                <h5 class="text-success fw-bold mb-0">Rp <?= number_format($grand_total, 0, ',', '.'); ?> <span class="badge bg-danger fs-6 ms-1"><?= $total_qty; ?> item</span></h5>
            </div>
            <div>
                <?php if(!empty($cart)): ?>
                    <button type="button" class="btn btn-danger fw-bold px-4 py-2 shadow" data-bs-toggle="modal" data-bs-target="#modalCheckout">
                        <i class="fa-solid fa-cart-shopping me-2"></i> Lihat Keranjang
                    </button>
                <?php else: ?>
                    <button type="button" class="btn btn-secondary fw-bold px-4 py-2" disabled>
                        <i class="fa-solid fa-cart-shopping me-2"></i> Keranjang Kosong
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ================= MODAL KERANJANG & CHECKOUT ================= -->
    <div class="modal fade" id="modalCheckout" tabindex="-1" aria-labelledby="modalCheckoutLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="modalCheckoutLabel"><i class="fa-solid fa-receipt me-2"></i> Keranjang Pesanan Anda</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- List Item di Keranjang -->
                    <ul class="list-group mb-3">
                        <?php if(!empty($cart)): foreach($cart as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="my-0 fw-bold"><?= $item['nama_menu']; ?></h6>
                                    <small class="text-muted"><?= $item['qty']; ?> x Rp <?= number_format($item['harga'], 0, ',', '.'); ?></small>
                                </div>
                                <div class="text-end">
                                    <span class="text-success fw-bold">Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></span><br>
                                    <a href="<?= site_url('pelanggan/remove_cart/' . $item['id'] . '/' . $no_meja); ?>" class="text-danger small text-decoration-none">Hapus</a>
                                </div>
                            </li>
                        <?php endforeach; endif; ?>
                    </ul>

                    <div class="d-flex justify-content-between mb-3 px-2">
                        <h5 class="fw-bold">Total Pembayaran:</h5>
                        <h5 class="text-success fw-bold">Rp <?= number_format($grand_total, 0, ',', '.'); ?></h5>
                    </div>

                    <hr>

                    <!-- Form Input Nama & Pembayaran -->
                    <?php if(!empty($cart)): ?>
                        <form action="<?= site_url('pelanggan/checkout/' . $no_meja); ?>" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Nama Pemesan:</label>
                                <input type="text" name="nama_pemesan" class="form-control" required placeholder="Masukkan nama Anda">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small">Pilih Metode Pembayaran:</label>
                                <select name="metode_pembayaran" id="metode_pembayaran" class="form-select" required onchange="toggleQrisInfo(this.value)">
                                    <option value="Cash">Bayar Tunai di Kasir (Cash)</option>
                                    <option value="QRIS">QRIS / Transfer (Scan dari HP)</option>
                                </select>
                            </div>

                            <!-- Kotak Simulasi QRIS -->
                            <div id="box_qris_info" class="card bg-light border p-3 mb-3 text-center" style="display: none;">
                                <h6 class="fw-bold text-danger mb-2">Simulasi QRIS Romeesa</h6>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=SIMULASI-PAYMENT-QRIS-AYAM-GEPREK-ROMEESA" alt="QRIS Dummy" class="img-fluid mx-auto mb-2" style="max-width: 140px;">
                                <p class="text-muted small mb-1">Silakan scan QR di atas untuk simulasi pembayaran.</p>
                                <p class="text-danger small fw-bold mb-0">Atau transfer via DANA: 0812-3456-7890 (a.n Romeesa)</p>
                            </div>

                            <button type="submit" class="btn btn-danger w-100 fw-bold py-2 shadow-sm">Kirim Pesanan ke Kasir</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Script JavaScript -->
    <script>
        function toggleQrisInfo(val) {
            let boxQris = document.getElementById('box_qris_info');
            if (val === 'QRIS') {
                boxQris.style.display = 'block';
            } else {
                boxQris.style.display = 'none';
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>