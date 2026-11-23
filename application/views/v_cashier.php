
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// =====================================================
// DATA KERANJANG
// =====================================================
$cart = $this->session->userdata('cart');

$cart = (is_array($cart)) ? $cart : [];

$grand_total = 0;

foreach ($cart as $item) {
    $grand_total += (float) ($item['subtotal'] ?? 0);
}

// =====================================================
// KATEGORI MENU
// =====================================================
$kategori = [
    'makanan'  => 'Makanan',
    'minuman'  => 'Minuman',
    'topping'  => 'Topping'
];

// =====================================================
// URL FOTO MENU
// =====================================================
$host_aktif = $_SERVER['HTTP_HOST'] ?? 'localhost';

$protokol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    ? 'https://'
    : 'http://';

$url_foto_kasir = $protokol . $host_aktif
    . '/ayam-geprek-romeesa-final/uploads/foto_menu/';

$total_menu = (isset($menu) && is_array($menu))
    ? count($menu)
    : 0;
?>

<!-- =====================================================
     NOTIFIKASI
====================================================== -->

<?php if ($this->session->flashdata('success')): ?>

    <div class="alert alert-success mb-3" role="alert">

        <i class="fa-solid fa-circle-check me-1" aria-hidden="true"></i>

        <?= html_escape($this->session->flashdata('success')); ?>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Tutup"
        ></button>

    </div>

<?php endif; ?>


<?php if ($this->session->flashdata('error')): ?>

    <div class="alert alert-danger mb-3" role="alert">

        <i class="fa-solid fa-triangle-exclamation me-1" aria-hidden="true"></i>

        <?= html_escape($this->session->flashdata('error')); ?>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Tutup"
        ></button>

    </div>

<?php endif; ?>


<!-- =====================================================
     LAYOUT POS
====================================================== -->

<div class="pos">

    <!-- =================================================
         DAFTAR MENU
    ================================================== -->

    <section class="pos-panel" data-filter>

        <div class="section-head">

            <div>
                <h3>Daftar Menu</h3>

                <p class="small text-muted mb-0">
                    <span data-filter-count><?= $total_menu; ?></span>
                    menu tersedia
                </p>
            </div>

            <!-- PENCARIAN MENU -->
            <div class="topbar-search" style="width:250px; max-width:100%;">

                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>

                <input
                    type="search"
                    data-filter-search
                    placeholder="Cari nama menu"
                    aria-label="Cari nama menu"
                >

            </div>

        </div>


        <!-- FILTER KATEGORI -->

        <div class="chip-row mb-3">

            <button
                type="button"
                class="chip is-active"
                data-filter-chip="semua"
                aria-pressed="true"
            >
                Semua
            </button>

            <button
                type="button"
                class="chip"
                data-filter-chip="makanan"
                aria-pressed="false"
            >
                Makanan
            </button>

            <button
                type="button"
                class="chip"
                data-filter-chip="minuman"
                aria-pressed="false"
            >
                Minuman
            </button>

            <button
                type="button"
                class="chip"
                data-filter-chip="topping"
                aria-pressed="false"
            >
                Topping
            </button>

        </div>


        <!-- GRID MENU -->

        <?php if (!empty($menu) && is_array($menu)): ?>

            <div class="dish-grid" data-filter-list>

                <?php foreach ($menu as $m): ?>

                    <?php
                    $nama_menu = $m['nama_menu'] ?? 'Menu';

                    $nama_foto = !empty($m['foto'])
                        ? basename($m['foto'])
                        : '';

                    $kategori_menu = strtolower(trim($m['kategori'] ?? ''));

                    $id_menu = $m['id'] ?? '';

                    $harga_menu = (float) ($m['harga'] ?? 0);
                    ?>

                    <article
                        class="dish"
                        data-filter-item
                        data-category="<?= html_escape($kategori_menu); ?>"
                        data-name="<?= html_escape(strtolower($nama_menu)); ?>"
                    >

                        <!-- FOTO MENU -->

                        <div class="dish-media">

                            <?php if (!empty($nama_foto)): ?>

                                <img
                                    src="<?= html_escape($url_foto_kasir . rawurlencode($nama_foto)); ?>"
                                    alt="<?= html_escape($nama_menu); ?>"
                                    loading="lazy"
                                >

                            <?php else: ?>

                                <div class="dish-thumb-placeholder">
                                    <i class="fa-solid fa-utensils" aria-hidden="true"></i>
                                </div>

                            <?php endif; ?>

                            <span class="dish-tag">
                                <?= html_escape($kategori[$kategori_menu] ?? ucfirst($kategori_menu)); ?>
                            </span>

                        </div>


                        <!-- INFORMASI MENU -->

                        <div class="dish-body">

                            <span class="dish-name">
                                <?= html_escape($nama_menu); ?>
                            </span>

                            <div class="dish-foot">

                                <span class="dish-price">
                                    Rp <?= number_format($harga_menu, 0, ',', '.'); ?>
                                </span>

                                <?php if ($id_menu !== ''): ?>

                                    <form
                                        action="<?= site_url('cashier/add_cart/' . $id_menu); ?>"
                                        method="POST"
                                        class="d-inline"
                                    >
                                        <input
                                            type="hidden"
                                            name="<?= $this->security->get_csrf_token_name(); ?>"
                                            value="<?= $this->security->get_csrf_hash(); ?>"
                                        >
                                        <button
                                            type="submit"
                                            class="dish-add"
                                            aria-label="Tambah <?= html_escape($nama_menu); ?> ke keranjang"
                                            title="Tambah ke keranjang"
                                        >
                                            <i class="fa-solid fa-plus" aria-hidden="true"></i>
                                        </button>
                                    </form>

                                <?php else: ?>

                                    <button
                                        type="button"
                                        class="dish-add"
                                        disabled
                                        aria-label="ID menu tidak tersedia"
                                    >
                                        <i class="fa-solid fa-plus" aria-hidden="true"></i>
                                    </button>

                                <?php endif; ?>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>


            <!-- MENU TIDAK DITEMUKAN -->

            <div class="empty-state" data-filter-empty hidden>

                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>

                <h4>Menu tidak ditemukan</h4>

                <p>
                    Coba kata kunci lain atau pilih kategori Semua.
                </p>

            </div>


        <?php else: ?>

            <div class="empty-state">

                <i class="fa-solid fa-box-open" aria-hidden="true"></i>

                <h4>Belum ada menu tersedia</h4>

                <p>
                    Tambahkan menu melalui halaman Kelola Menu milik owner,
                    lalu muat ulang halaman kasir.
                </p>

            </div>

        <?php endif; ?>

    </section>


    <!-- =================================================
         KERANJANG PESANAN
    ================================================== -->

    <aside class="panel panel-sticky">

        <!-- HEADER KERANJANG -->

        <div class="panel-head d-flex align-items-center justify-content-between">

            <h3>
                <i class="fa-solid fa-cart-shopping me-2" aria-hidden="true"></i>
                Keranjang Pesanan
            </h3>

            <?php if (!empty($cart)): ?>

                <span class="badge-soft is-brand">
                    <?= count($cart); ?> menu
                </span>

            <?php endif; ?>

        </div>


        <!-- ISI KERANJANG -->

        <div class="panel-body">

            <?php if (!empty($cart)): ?>

                <?php foreach ($cart as $item): ?>

                    <?php
                    $id_cart = $item['id'] ?? '';

                    $nama_cart = $item['nama_menu'] ?? 'Menu';

                    $harga_cart = (float) ($item['harga'] ?? 0);

                    $qty_cart = (int) ($item['qty'] ?? 0);

                    $subtotal_cart = (float) ($item['subtotal'] ?? 0);
                    ?>

                    <div class="cart-line">

                        <!-- INFORMASI MENU -->

                        <div class="me-auto">

                            <div class="line-name">
                                <?= html_escape($nama_cart); ?>
                            </div>

                            <div class="line-price">
                                <?= $qty_cart; ?>x @ Rp
                                <?= number_format($harga_cart, 0, ',', '.'); ?>
                            </div>

                        </div>


                        <!-- SUBTOTAL DAN HAPUS -->

                        <div class="text-end">

                            <div class="line-sub money">
                                Rp <?= number_format($subtotal_cart, 0, ',', '.'); ?>
                            </div>

                            <?php if ($id_cart !== ''): ?>

                                <form
                                    action="<?= site_url('cashier/remove_cart/' . $id_cart); ?>"
                                    method="POST"
                                    class="d-inline"
                                >
                                    <input
                                        type="hidden"
                                        name="<?= $this->security->get_csrf_token_name(); ?>"
                                        value="<?= $this->security->get_csrf_hash(); ?>"
                                    >
                                    <button
                                        type="submit"
                                        class="small text-danger border-0 bg-transparent p-0"
                                        aria-label="Hapus <?= html_escape($nama_cart); ?> dari keranjang"
                                        onclick="return confirm('Hapus <?= html_escape($nama_cart); ?> dari keranjang?');"
                                    >
                                        <i class="fa-solid fa-trash-can me-1" aria-hidden="true"></i>
                                        Hapus
                                    </button>
                                </form>

                            <?php endif; ?>

                        </div>

                    </div>

                <?php endforeach; ?>


            <?php else: ?>

                <div class="empty-state py-4">

                    <i class="fa-solid fa-basket-shopping" aria-hidden="true"></i>

                    <h4>Keranjang masih kosong</h4>

                    <p>
                        Tekan tombol tambah pada menu untuk memulai transaksi.
                    </p>

                </div>

            <?php endif; ?>

        </div>


        <!-- =================================================
             TOTAL DAN CHECKOUT
        ================================================== -->

        <div class="panel-foot">

            <!-- TOTAL -->

            <div class="sum-row is-total mb-3">

                <span>Total pembayaran</span>

                <span class="value money">
                    Rp <?= number_format($grand_total, 0, ',', '.'); ?>
                </span>

            </div>


            <?php if (!empty($cart)): ?>

                <form
                    action="<?= site_url('cashier/checkout'); ?>"
                    method="POST"
                    id="form_checkout"
                >

                    <input
                        type="hidden"
                        name="<?= $this->security->get_csrf_token_name(); ?>"
                        value="<?= $this->security->get_csrf_hash(); ?>"
                    >

                    <!-- NAMA PEMESAN -->

                    <div class="mb-3">

                        <label class="form-label" for="nama_pemesan">
                            Nama pemesan
                        </label>

                        <input
                            type="text"
                            id="nama_pemesan"
                            name="nama_pemesan"
                            class="form-control"
                            required
                            maxlength="100"
                            placeholder="Contoh: Budi"
                            autocomplete="off"
                        >

                    </div>


                    <!-- JENIS PESANAN -->

                    <div class="mb-3">

                        <span class="form-label d-block">
                            Jenis pesanan
                        </span>

                        <div class="pay-options">

                            <input
                                type="radio"
                                id="jenis_takeaway"
                                name="jenis_pesanan"
                                value="Takeaway"
                                checked
                            >

                            <label for="jenis_takeaway">
                                <i class="fa-solid fa-bag-shopping me-1" aria-hidden="true"></i>
                                Bungkus
                            </label>


                            <input
                                type="radio"
                                id="jenis_dinein"
                                name="jenis_pesanan"
                                value="Dine-in"
                            >

                            <label for="jenis_dinein">
                                <i class="fa-solid fa-chair me-1" aria-hidden="true"></i>
                                Dine-in
                            </label>

                        </div>

                    </div>


                    <!-- NOMOR MEJA -->

                    <div class="mb-3" id="box_meja" hidden>

                        <label class="form-label" for="nomor_meja">
                            Nomor meja
                        </label>

                        <input
                            type="number"
                            id="nomor_meja"
                            name="nomor_meja"
                            class="form-control"
                            min="1"
                            step="1"
                            placeholder="Contoh: 3"
                        >

                        <div class="form-text">
                            Masukkan nomor meja pelanggan.
                        </div>

                    </div>


                    <!-- METODE PEMBAYARAN -->

                    <div class="mb-3">

                        <span class="form-label d-block">
                            Metode pembayaran
                        </span>

                        <div class="pay-options">

                            <input
                                type="radio"
                                id="bayar_cash"
                                name="metode_pembayaran"
                                value="Cash"
                                checked
                            >

                            <label for="bayar_cash">
                                <i class="fa-solid fa-money-bill-wave me-1" aria-hidden="true"></i>
                                Tunai
                            </label>


                            <input
                                type="radio"
                                id="bayar_qris"
                                name="metode_pembayaran"
                                value="QRIS"
                            >

                            <label for="bayar_qris">
                                <i class="fa-solid fa-qrcode me-1" aria-hidden="true"></i>
                                QRIS
                            </label>

                        </div>

                    </div>


                    <!-- UANG TUNAI -->

                    <div class="mb-3" id="box_uang">

                        <label class="form-label" for="uang_bayar">
                            Uang pembeli (Rp)
                        </label>

                        <input
                            type="number"
                            id="uang_bayar"
                            name="uang_bayar"
                            class="form-control"
                            min="<?= (int) $grand_total; ?>"
                            step="1"
                            placeholder="Contoh: 50000"
                            required
                        >

                        <div class="form-text">
                            Uang pembeli tidak boleh kurang dari total pembayaran.
                        </div>

                    </div>


                    <!-- TOMBOL CHECKOUT -->

                    <button
                        type="submit"
                        class="btn btn-primary btn-lg w-100 mt-2"
                        id="btn_checkout"
                    >
                        <i class="fa-solid fa-circle-check me-2" aria-hidden="true"></i>
                        Proses Pembayaran
                    </button>

                </form>


            <?php else: ?>

                <button
                    type="button"
                    class="btn btn-light btn-lg w-100"
                    disabled
                >
                    <i class="fa-solid fa-cart-plus me-2" aria-hidden="true"></i>
                    Pilih menu dulu
                </button>

            <?php endif; ?>

        </div>

    </aside>

</div>


<!-- =====================================================
     JAVASCRIPT
====================================================== -->

<script>
(function () {
    'use strict';

    // =================================================
    // FILTER MENU
    // =================================================

    var filterContainer = document.querySelector('[data-filter]');

    if (filterContainer) {

        var searchInput = filterContainer.querySelector('[data-filter-search]');
        var filterItems = filterContainer.querySelectorAll('[data-filter-item]');
        var filterChips = filterContainer.querySelectorAll('[data-filter-chip]');
        var emptyState = filterContainer.querySelector('[data-filter-empty]');
        var filterCount = filterContainer.querySelector('[data-filter-count]');

        var kategoriAktif = 'semua';

        function filterMenu() {

            var keyword = searchInput
                ? searchInput.value.trim().toLowerCase()
                : '';

            var jumlahTampil = 0;

            Array.prototype.forEach.call(filterItems, function (item) {

                var nama = (item.getAttribute('data-name') || '').toLowerCase();
                var kategori = (item.getAttribute('data-category') || '').toLowerCase();

                var cocokNama = nama.indexOf(keyword) !== -1;

                var cocokKategori = kategoriAktif === 'semua'
                    || kategori === kategoriAktif;

                var tampil = cocokNama && cocokKategori;

                item.hidden = !tampil;

                if (tampil) {
                    jumlahTampil++;
                }

            });

            if (filterCount) {
                filterCount.textContent = jumlahTampil;
            }

            if (emptyState) {
                emptyState.hidden = jumlahTampil > 0;
            }
        }

        Array.prototype.forEach.call(filterChips, function (chip) {

            chip.addEventListener('click', function () {

                kategoriAktif = chip.getAttribute('data-filter-chip') || 'semua';

                Array.prototype.forEach.call(filterChips, function (button) {

                    var aktif = button === chip;

                    button.classList.toggle('is-active', aktif);
                    button.setAttribute('aria-pressed', aktif ? 'true' : 'false');

                });

                filterMenu();

            });

        });

        if (searchInput) {
            searchInput.addEventListener('input', filterMenu);
        }

        filterMenu();
    }


    // =================================================
    // FORM CHECKOUT
    // =================================================

    var formCheckout = document.getElementById('form_checkout');

    if (!formCheckout) {
        return;
    }

    var jenisPesanan = document.querySelectorAll('input[name="jenis_pesanan"]');
    var metodePembayaran = document.querySelectorAll('input[name="metode_pembayaran"]');

    var boxMeja = document.getElementById('box_meja');
    var boxUang = document.getElementById('box_uang');

    var inputMeja = document.getElementElementById
        ? document.getElementById('nomor_meja')
        : document.getElementById('nomor_meja');

    var inputUang = document.getElementById('uang_bayar');
    var tombolCheckout = document.getElementById('btn_checkout');

    function nilaiTerpilih(nodeList) {

        for (var i = 0; i < nodeList.length; i++) {

            if (nodeList[i].checked) {
                return nodeList[i].value;
            }

        }

        return '';
    }


    // =================================================
    // SINKRON INPUT
    // =================================================

    function sinkronForm() {

        var dineIn = nilaiTerpilih(jenisPesanan) === 'Dine-in';

        if (boxMeja && inputMeja) {

            boxMeja.hidden = !dineIn;
            inputMeja.required = dineIn;

            if (!dineIn) {
                inputMeja.value = '';
            }

        }


        var tunai = nilaiTerpilih(metodePembayaran) === 'Cash';

        if (boxUang && inputUang) {

            boxUang.hidden = !tunai;
            inputUang.required = tunai;

            if (!tunai) {
                inputUang.value = '';
            }

        }

    }


    Array.prototype.forEach.call(jenisPesanan, function (radio) {
        radio.addEventListener('change', sinkronForm);
    });

    Array.prototype.forEach.call(metodePembayaran, function (radio) {
        radio.addEventListener('change', sinkronForm);
    });


    // =================================================
    // VALIDASI CHECKOUT
    // =================================================

    formCheckout.addEventListener('submit', function (event) {

        var dineIn = nilaiTerpilih(jenisPesanan) === 'Dine-in';
        var tunai = nilaiTerpilih(metodePembayaran) === 'Cash';

        if (dineIn && (!inputMeja.value || parseInt(inputMeja.value, 10) < 1)) {

            event.preventDefault();

            alert('Nomor meja harus diisi dengan benar.');

            inputMeja.focus();

            return;
        }


        if (tunai && (!inputUang.value || Number(inputUang.value) < Number(inputUang.min))) {

            event.preventDefault();

            alert('Uang pembeli tidak boleh kurang dari total pembayaran.');

            inputUang.focus();

            return;
        }


        if (!confirm('Lanjutkan proses pembayaran transaksi ini?')) {

            event.preventDefault();

            return;
        }


        if (tombolCheckout) {

            tombolCheckout.disabled = true;

            tombolCheckout.innerHTML =
                '<i class="fa-solid fa-spinner fa-spin me-2"></i>Memproses pembayaran...';

        }

    });


    // Jalankan saat halaman pertama dibuka
    sinkronForm();

})();
</script>
