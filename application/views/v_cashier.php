<!-- Notifikasi Flash Message -->
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
    <!-- DAFTAR MENU (KIRI) -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4 p-4 bg-white">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="fa-solid fa-utensils text-danger me-2"></i> Daftar Menu Restoran
                </h5>
                <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill small">
                    Pilih menu untuk dimasukkan ke keranjang
                </span>
            </div>

            <div class="row g-3">
                <?php if(!empty($menu)): ?>
                    <?php foreach($menu as $m): ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="card h-100 shadow-sm border border-light rounded-4 bg-white overflow-hidden transition-hover">
                                <!-- Foto Menu -->
                                <?php if(!empty($m['foto'])): ?>
                                    <img src="<?= base_url('uploads/' . $m['foto']); ?>" alt="<?= $m['nama_menu']; ?>" class="card-img-top" style="height: 140px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light text-secondary d-flex align-items-center justify-content-center" style="height: 140px; font-size: 12px;">
                                        <i class="fa-solid fa-image fa-2x opacity-50"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="card-body d-flex flex-column justify-content-between p-3">
                                    <div>
                                        <span class="badge bg-danger bg-opacity-10 text-danger mb-2 px-2 py-1 rounded-pill" style="font-size: 10px;"><?= ucfirst($m['kategori']); ?></span>
                                        <h6 class="card-title fw-bold text-dark mb-1 text-truncate" title="<?= $m['nama_menu']; ?>"><?= $m['nama_menu']; ?></h6>
                                        <p class="card-text text-success fw-bold mb-3 fs-6">Rp <?= number_format($m['harga'], 0, ',', '.'); ?></p>
                                    </div>
                                    <a href="<?= site_url('cashier/add_cart/' . $m['id']); ?>" class="btn btn-outline-danger btn-sm w-100 rounded-pill fw-semibold">
                                        <i class="fa-solid fa-plus me-1"></i> Pilih Menu
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

    <!-- KERANJANG PESANAN (KANAN) -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 rounded-4 p-4 bg-white position-sticky" style="top: 20px;">
            <h5 class="fw-bold text-dark mb-3">
                <i class="fa-solid fa-cart-shopping text-danger me-2"></i> Keranjang Pesanan
            </h5>
            <hr class="text-secondary opacity-25">

            <!-- Daftar Item di Keranjang -->
            <div style="max-height: 240px; overflow-y: auto;" class="pe-1">
                <ul class="list-group list-group-flush mb-3">
                    <?php 
                    $cart = $this->session->userdata('cart');
                    $grand_total = 0;
                    if(!empty($cart)): 
                        foreach($cart as $item): 
                            $grand_total += $item['subtotal'];
                    ?>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent">
                            <div>
                                <h6 class="my-0 fs-6 fw-bold text-dark"><?= $item['nama_menu']; ?></h6>
                                <small class="text-muted"><?= $item['qty']; ?>x @ Rp <?= number_format($item['harga'], 0, ',', '.'); ?></small>
                            </div>
                            <div class="text-end">
                                <span class="text-success fw-bold fs-6">Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></span>
                                <br>
                                <a href="<?= site_url('cashier/remove_cart/' . $item['id']); ?>" class="text-danger small text-decoration-none fw-semibold" style="font-size: 11px;">
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
                <form action="<?= site_url('cashier/checkout'); ?>" method="POST">
                    <!-- Nama Pemesan -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Nama Pemesan:</label>
                        <input type="text" name="nama_pemesan" class="form-control rounded-3 form-control-sm py-2" required placeholder="Contoh: Budi">
                    </div>

                    <!-- Jenis Pesanan -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Jenis Pesanan:</label>
                        <select name="jenis_pesanan" id="jenis_pesanan" class="form-select rounded-3 form-select-sm py-2" required onchange="toggleMeja(this.value)">
                            <option value="Takeaway">Bungkus (Takeaway)</option>
                            <option value="Dine-in">Makan di Tempat (Dine-in)</option>
                        </select>
                    </div>

                    <!-- Nomor Meja (Muncul jika Dine-in) -->
                    <div class="mb-3" id="box_meja" style="display: none;">
                        <label class="form-label fw-bold small text-secondary">Nomor Meja:</label>
                        <input type="number" name="nomor_meja" class="form-control rounded-3 form-control-sm py-2" placeholder="Contoh: 3">
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Metode Pembayaran:</label>
                        <select name="metode_pembayaran" id="metode_pembayaran" class="form-select rounded-3 form-select-sm py-2" onchange="toggleBayar(this.value)">
                            <option value="Cash">Cash (Tunai)</option>
                            <option value="QRIS">QRIS / Transfer</option>
                        </select>
                    </div>

                    <!-- Uang Bayar (Muncul jika Cash) -->
                    <div class="mb-4" id="box_uang">
                        <label class="form-label fw-bold small text-secondary">Uang Pembeli (Rp):</label>
                        <input type="number" name="uang_bayar" class="form-control rounded-3 form-control-sm py-2" placeholder="Contoh: 50000">
                    </div>

                    <button type="submit" class="btn btn-danger w-100 rounded-pill py-2.5 fw-bold shadow-sm">
                        <i class="fa-solid fa-circle-check me-2"></i> Proses Pembayaran
                    </button>
                </form>
            <?php else: ?>
                <button class="btn btn-secondary w-100 rounded-pill py-2.5 fw-bold opacity-50" disabled>
                    Proses Pembayaran
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Script untuk interaksi form dinamis -->
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