<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$cart = $this->session->userdata('cart');
$grand_total = 0;
if (!empty($cart)) {
    foreach ($cart as $item) {
        $grand_total += $item['subtotal'];
    }
}
$kategori = ['makanan' => 'Makanan', 'minuman' => 'Minuman', 'topping' => 'Topping'];
?>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success mb-3" role="alert">
        <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
        <span><?= html_escape($this->session->flashdata('success')); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger mb-3" role="alert">
        <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
        <span><?= html_escape($this->session->flashdata('error')); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
<?php endif; ?>

<div class="pos">

    <!-- DAFTAR MENU -->
    <section class="pos-panel" data-filter>
        <div class="section-head">
            <div>
                <h3>Daftar Menu</h3>
                <p class="small text-muted mb-0">
                    <span data-filter-count><?= is_array($menu) ? count($menu) : 0; ?></span> menu tersedia
                </p>
            </div>

            <div class="topbar-search" style="width: 250px; max-width: 100%;">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                <input type="search" data-filter-search placeholder="Cari nama menu" aria-label="Cari nama menu">
            </div>
        </div>

        <div class="chip-row mb-3">
            <button type="button" class="chip is-active" data-filter-chip="semua" aria-pressed="true">Semua</button>
            <button type="button" class="chip" data-filter-chip="makanan" aria-pressed="false">Makanan</button>
            <button type="button" class="chip" data-filter-chip="minuman" aria-pressed="false">Minuman</button>
            <button type="button" class="chip" data-filter-chip="topping" aria-pressed="false">Topping</button>
        </div>

        <?php if (!empty($menu)): ?>
            <div class="dish-grid">
                <?php foreach ($menu as $m): ?>
                    <article class="dish"
                             data-filter-item
                             data-category="<?= html_escape($m['kategori']); ?>"
                             data-name="<?= html_escape(strtolower($m['nama_menu'])); ?>">

                        <div class="dish-media">
                            <?php if (!empty($m['foto'])): ?>
                                <img src="<?= base_url('uploads/' . $m['foto']); ?>" alt="<?= html_escape($m['nama_menu']); ?>">
                            <?php endif; ?>
                            <span class="dish-tag"><?= $kategori[$m['kategori']] ?? ucfirst($m['kategori']); ?></span>
                        </div>

                        <div class="dish-body">
                            <span class="dish-name"><?= html_escape($m['nama_menu']); ?></span>
                            <div class="dish-foot">
                                <span class="dish-price">Rp <?= number_format($m['harga'], 0, ',', '.'); ?></span>
                                <a class="dish-add" href="<?= site_url('cashier/add_cart/' . $m['id']); ?>"
                                   aria-label="Tambah <?= html_escape($m['nama_menu']); ?> ke keranjang">
                                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="empty-state" data-filter-empty hidden>
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                <h4>Menu tidak ditemukan</h4>
                <p>Coba kata kunci lain atau pilih kategori Semua.</p>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fa-solid fa-box-open" aria-hidden="true"></i>
                <h4>Belum ada menu tersedia</h4>
                <p>Tambahkan menu lewat halaman Kelola Menu milik owner, lalu muat ulang halaman ini.</p>
            </div>
        <?php endif; ?>
    </section>

    <!-- KERANJANG -->
    <aside class="panel panel-sticky">
        <div class="panel-head d-flex align-items-center justify-content-between">
            <h3><i class="fa-solid fa-cart-shopping me-2" aria-hidden="true"></i>Keranjang Pesanan</h3>
            <?php if (!empty($cart)): ?>
                <span class="badge-soft is-brand"><?= count($cart); ?> item</span>
            <?php endif; ?>
        </div>

        <div class="panel-body">
            <?php if (!empty($cart)): ?>
                <?php foreach ($cart as $item): ?>
                    <div class="cart-line">
                        <div class="me-auto">
                            <div class="line-name"><?= html_escape($item['nama_menu']); ?></div>
                            <div class="line-price"><?= $item['qty']; ?>x @ Rp <?= number_format($item['harga'], 0, ',', '.'); ?></div>
                        </div>
                        <div class="text-end">
                            <div class="line-sub money">Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></div>
                            <a class="small text-danger" href="<?= site_url('cashier/remove_cart/' . $item['id']); ?>"
                               aria-label="Hapus <?= html_escape($item['nama_menu']); ?> dari keranjang">
                                <i class="fa-solid fa-trash-can me-1" aria-hidden="true"></i>Hapus
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state py-4">
                    <i class="fa-solid fa-basket-shopping" aria-hidden="true"></i>
                    <h4>Keranjang masih kosong</h4>
                    <p>Tekan tombol tambah pada menu untuk memulai transaksi.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="panel-foot">
            <div class="sum-row is-total mb-3">
                <span>Total pembayaran</span>
                <span class="value money">Rp <?= number_format($grand_total, 0, ',', '.'); ?></span>
            </div>

            <?php if (!empty($cart)): ?>
                <form action="<?= site_url('cashier/checkout'); ?>" method="POST">

                    <div class="mb-3">
                        <label class="form-label" for="nama_pemesan">Nama pemesan</label>
                        <input type="text" id="nama_pemesan" name="nama_pemesan" class="form-control" required
                               placeholder="Contoh: Budi" autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <span class="form-label d-block">Jenis pesanan</span>
                        <div class="pay-options">
                            <input type="radio" id="jenis_takeaway" name="jenis_pesanan" value="Takeaway" checked>
                            <label for="jenis_takeaway"><i class="fa-solid fa-bag-shopping me-1" aria-hidden="true"></i> Bungkus</label>

                            <input type="radio" id="jenis_dinein" name="jenis_pesanan" value="Dine-in">
                            <label for="jenis_dinein"><i class="fa-solid fa-chair me-1" aria-hidden="true"></i> Dine-in</label>
                        </div>
                    </div>

                    <div class="mb-3" id="box_meja" hidden>
                        <label class="form-label" for="nomor_meja">Nomor meja</label>
                        <input type="number" id="nomor_meja" name="nomor_meja" class="form-control" min="1" placeholder="Contoh: 3">
                    </div>

                    <div class="mb-3">
                        <span class="form-label d-block">Metode pembayaran</span>
                        <div class="pay-options">
                            <input type="radio" id="bayar_cash" name="metode_pembayaran" value="Cash" checked>
                            <label for="bayar_cash"><i class="fa-solid fa-money-bill-wave me-1" aria-hidden="true"></i> Tunai</label>

                            <input type="radio" id="bayar_qris" name="metode_pembayaran" value="QRIS">
                            <label for="bayar_qris"><i class="fa-solid fa-qrcode me-1" aria-hidden="true"></i> QRIS</label>
                        </div>
                    </div>

                    <div class="mb-3" id="box_uang">
                        <label class="form-label" for="uang_bayar">Uang pembeli (Rp)</label>
                        <input type="number" id="uang_bayar" name="uang_bayar" class="form-control" min="0"
                               placeholder="Contoh: 50000" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 mt-2">
                        <i class="fa-solid fa-circle-check me-2" aria-hidden="true"></i> Proses Pembayaran
                    </button>
                </form>
            <?php else: ?>
                <button type="button" class="btn btn-light btn-lg w-100" disabled>
                    <i class="fa-solid fa-cart-plus me-2" aria-hidden="true"></i> Pilih menu dulu
                </button>
            <?php endif; ?>
        </div>
    </aside>
</div>

<script>
    (function () {
        var jenis = document.querySelectorAll('input[name="jenis_pesanan"]');
        var metode = document.querySelectorAll('input[name="metode_pembayaran"]');
        var boxMeja = document.getElementById('box_meja');
        var boxUang = document.getElementById('box_uang');
        var inputMeja = document.getElementById('nomor_meja');
        var inputUang = document.getElementById('uang_bayar');

        function nilai(nodeList) {
            for (var i = 0; i < nodeList.length; i++) {
                if (nodeList[i].checked) return nodeList[i].value;
            }
            return '';
        }

        function sinkron() {
            var dineIn = nilai(jenis) === 'Dine-in';
            boxMeja.hidden = !dineIn;
            inputMeja.required = dineIn;
            if (!dineIn) inputMeja.value = '';

            var tunai = nilai(metode) === 'Cash';
            boxUang.hidden = !tunai;
            inputUang.required = tunai;
            if (!tunai) inputUang.value = '';
        }

        Array.prototype.forEach.call(jenis, function (n) { n.addEventListener('change', sinkron); });
        Array.prototype.forEach.call(metode, function (n) { n.addEventListener('change', sinkron); });
        sinkron();
    })();
</script>
