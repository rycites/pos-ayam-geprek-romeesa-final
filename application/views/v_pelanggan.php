
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$cart = isset($cart) && is_array($cart) ? $cart : [];

$sambal_tersedia = isset($sambal_tersedia) && is_array($sambal_tersedia)
    ? $sambal_tersedia
    : [];

// Samakan nama nomor meja dengan data dari controller
$no_meja = isset($nomor_meja) ? $nomor_meja : (isset($no_meja) ? $no_meja : 1);

$grand_total = 0;
$total_qty   = 0;

foreach ($cart as $item) {
    $grand_total += (int)$item['subtotal'];
    $total_qty   += (int)(isset($item['jumlah']) ? $item['jumlah'] : 0);
}

$label_kategori = [
    'makanan'  => 'Makanan',
    'minuman'  => 'Minuman',
    'topping'  => 'Topping'
];

$host_aktif = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$protokol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$base_url_aktif = $protokol . $host_aktif . '/ayam-geprek-romeesa-final/';
$folder_foto_menu = 'uploads/foto_menu/';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesan Menu - Meja <?= html_escape($no_meja); ?> | Ayam Geprek Romeesa</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="<?= base_url('assets/css/theme.css'); ?>">
</head>

<body class="customer-body">

<header class="customer-topbar">
    <div class="customer-topbar-inner">

        <div class="brand">
            <span class="brand-mark">
                <i class="fa-solid fa-utensils"></i>
            </span>

            <span class="brand-text">
                <strong>Geprek Romeesa</strong>
                <small>Pesan langsung dari meja</small>
            </span>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="table-chip">
                <i class="fa-solid fa-chair"></i>
                Meja <?= html_escape($no_meja); ?>
            </span>

            <button type="button"
                    class="icon-btn icon-btn-dark position-relative"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#cartCanvas"
                    aria-controls="cartCanvas">

                <i class="fa-solid fa-cart-shopping"></i>
                <span class="visually-hidden">Buka keranjang</span>

                <?php if ($total_qty > 0): ?>
                    <span class="cart-badge"><?= $total_qty; ?></span>
                <?php endif; ?>
            </button>
        </div>

    </div>
</header>

<div class="customer-wrap" data-filter>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success mb-3">
            <?= html_escape($this->session->flashdata('success')); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger mb-3">
            <?= html_escape($this->session->flashdata('error')); ?>
        </div>
    <?php endif; ?>

    <!-- HEADER MENU -->
    <div class="section-head">
        <div>
            <h3>Menu tersedia</h3>
            <p class="small text-muted mb-0">
                <span data-filter-count><?= is_array($menu) ? count($menu) : 0; ?></span>
                pilihan menu
            </p>
        </div>

        <div class="topbar-search" style="width:240px;max-width:100%;">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="search"
                   data-filter-search
                   placeholder="Cari menu"
                   aria-label="Cari menu">
        </div>
    </div>

    <!-- FILTER KATEGORI -->
    <div class="chip-row mb-3">
        <button type="button" class="chip is-active" data-filter-chip="semua">Semua</button>
        <button type="button" class="chip" data-filter-chip="makanan">Makanan</button>
        <button type="button" class="chip" data-filter-chip="minuman">Minuman</button>
        <button type="button" class="chip" data-filter-chip="topping">Topping</button>
    </div>

    <!-- DAFTAR MENU -->
    <?php if (!empty($menu)): ?>

        <div class="dish-grid">

            <?php foreach ($menu as $m): ?>

                <?php
                $habis = isset($m['status']) && $m['status'] === 'habis';

                $pakai_sambal = !empty($m['pakai_sambal']);
                $pakai_level  = !empty($m['pakai_level']);
                $punya_opsi   = $pakai_sambal || $pakai_level;
                $sambal_kosong = $pakai_sambal && empty($sambal_tersedia);

                // Mendukung nama kolom foto yang berbeda
                $nama_foto = !empty($m['gambar'])
                    ? $m['gambar']
                    : (isset($m['foto']) ? $m['foto'] : '');

                $foto_menu = !empty($nama_foto)
                    ? $base_url_aktif . $folder_foto_menu . rawurlencode(basename($nama_foto))
                    : '';
                ?>

                <article class="dish<?= $habis ? ' is-habis' : ''; ?>"
                         data-filter-item
                         data-category="<?= html_escape(isset($m['kategori']) ? $m['kategori'] : ''); ?>"
                         data-name="<?= html_escape(strtolower($m['nama_menu'])); ?>">

                    <div class="dish-media">
                        <?php if ($foto_menu): ?>
                            <img src="<?= html_escape($foto_menu); ?>"
                                 alt="<?= html_escape($m['nama_menu']); ?>">
                        <?php endif; ?>

                        <span class="dish-tag">
                            <?= isset($label_kategori[$m['kategori']]) ? $label_kategori[$m['kategori']] : 'Menu'; ?>
                        </span>
                    </div>

                    <div class="dish-body">

                        <?php if ($habis): ?>
                            <span class="badge-soft is-danger align-self-start">Sedang habis</span>
                        <?php endif; ?>

                        <span class="dish-name">
                            <?= html_escape($m['nama_menu']); ?>
                        </span>

                        <div class="dish-foot">
                            <span class="dish-price">
                                Rp <?= number_format($m['harga'], 0, ',', '.'); ?>
                            </span>

                            <?php if ($habis): ?>
                                <button type="button" class="dish-add" disabled>
                                    <i class="fa-solid fa-ban"></i>
                                </button>
                            <?php elseif ($sambal_kosong): ?>
                                <button type="button"
                                        class="dish-add"
                                        disabled
                                        title="Sambal belum tersedia"
                                        aria-label="Sambal belum tersedia untuk <?= html_escape($m['nama_menu']); ?>">
                                    <i class="fa-solid fa-ban"></i>
                                </button>
                            <?php elseif ($punya_opsi): ?>
                                <button type="button"
                                        class="dish-add"
                                        data-bs-toggle="modal"
                                        data-bs-target="#opsiPelanggan<?= (int) $m['id']; ?>"
                                        aria-label="Pilih sambal atau level untuk <?= html_escape($m['nama_menu']); ?>"
                                        title="Pilih sambal / level">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            <?php else: ?>
                                <form action="<?= site_url('pelanggan/add_cart/' . $m['id'] . '/' . $no_meja); ?>"
                                      method="POST"
                                      class="d-inline">
                                    <input type="hidden"
                                           name="<?= $this->security->get_csrf_token_name(); ?>"
                                           value="<?= $this->security->get_csrf_hash(); ?>">
                                    <button type="submit"
                                            class="dish-add"
                                            aria-label="Tambah <?= html_escape($m['nama_menu']); ?>">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>

            <?php endforeach; ?>

        </div>

        <div class="empty-state" data-filter-empty hidden>
            <i class="fa-solid fa-magnifying-glass"></i>
            <h4>Menu tidak ditemukan</h4>
            <p>Coba kata kunci lain atau pilih kategori Semua.</p>
        </div>

    <?php else: ?>

        <div class="card">
            <div class="empty-state">
                <i class="fa-solid fa-box-open"></i>
                <h4>Belum ada menu tersedia</h4>
                <p>Menu sedang diperbarui. Silakan panggil petugas kami.</p>
            </div>
        </div>

    <?php endif; ?>

    <!-- CEK STATUS PESANAN -->
    <div class="text-center mt-4">
        <a href="<?= site_url('pelanggan/cek_status_aktif/' . $no_meja); ?>"
           class="btn btn-light">
            <i class="fa-solid fa-clock-rotate-left me-2"></i>
            Cek status pesanan meja ini
        </a>
    </div>

</div>

<!-- MODAL PILIHAN SAMBAL DAN LEVEL PEDAS -->
<?php if (!empty($menu)): ?>

    <?php foreach ($menu as $m): ?>

        <?php
        $id_menu_opsi = (int) ($m['id'] ?? 0);
        $pakai_sambal_opsi = !empty($m['pakai_sambal']);
        $pakai_level_opsi  = !empty($m['pakai_level']);
        $sambal_kosong_opsi = $pakai_sambal_opsi && empty($sambal_tersedia);
        ?>

        <?php if (
            $id_menu_opsi > 0 &&
            ($pakai_sambal_opsi || $pakai_level_opsi) &&
            !$sambal_kosong_opsi
        ): ?>

            <div class="modal fade"
                 id="opsiPelanggan<?= $id_menu_opsi; ?>"
                 tabindex="-1"
                 aria-labelledby="opsiPelangganLabel<?= $id_menu_opsi; ?>"
                 aria-hidden="true">

                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <form action="<?= site_url('pelanggan/add_cart/' . $id_menu_opsi . '/' . $no_meja); ?>"
                              method="POST">

                            <input type="hidden"
                                   name="<?= $this->security->get_csrf_token_name(); ?>"
                                   value="<?= $this->security->get_csrf_hash(); ?>">

                            <div class="modal-header">
                                <h5 class="modal-title" id="opsiPelangganLabel<?= $id_menu_opsi; ?>">
                                    Pilihan <?= html_escape($m['nama_menu']); ?>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>

                            <div class="modal-body">
                                <?php if ($pakai_sambal_opsi): ?>
                                    <div class="mb-3">
                                        <label class="form-label" for="sambal_pelanggan_<?= $id_menu_opsi; ?>">
                                            Sambal
                                        </label>
                                        <select id="sambal_pelanggan_<?= $id_menu_opsi; ?>"
                                                name="id_sambal"
                                                class="form-select"
                                                required>
                                            <option value="">Pilih sambal</option>
                                            <?php foreach ($sambal_tersedia as $s): ?>
                                                <option value="<?= (int) $s['id']; ?>">
                                                    <?= html_escape($s['nama_sambal']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php endif; ?>

                                <?php if ($pakai_level_opsi): ?>
                                    <div class="mb-0">
                                        <label class="form-label" for="level_pelanggan_<?= $id_menu_opsi; ?>">
                                            Level pedas
                                        </label>
                                        <select id="level_pelanggan_<?= $id_menu_opsi; ?>"
                                                name="level_pedas"
                                                class="form-select"
                                                required>
                                            <option value="">Pilih level</option>
                                            <?php for ($level = 1; $level <= 6; $level++): ?>
                                                <option value="<?= $level; ?>">Level <?= $level; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <p class="text-muted small mb-0 mt-1">
                                            Level pedas tidak menambah biaya.
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Tambah ke keranjang</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        <?php endif; ?>

    <?php endforeach; ?>

<?php endif; ?>

<!-- FLOATING CART -->
<?php if ($total_qty > 0): ?>

    <div class="floating-cart d-lg-none">
        <div class="inner">
            <div>
                <span class="badge-soft is-brand">
                    <?= $total_qty; ?> item
                </span>

                <div class="fw-extrabold money">
                    Rp <?= number_format($grand_total, 0, ',', '.'); ?>
                </div>
            </div>

            <button type="button"
                    class="btn btn-primary ms-auto"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#cartCanvas">
                <i class="fa-solid fa-cart-shopping me-2"></i>
                Lihat keranjang
            </button>
        </div>
    </div>

<?php endif; ?>

<!-- PANEL KERANJANG -->
<aside class="offcanvas offcanvas-end"
      tabindex="-1"
      id="cartCanvas"
      aria-labelledby="cartCanvasLabel">

    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="cartCanvasLabel">
            Keranjang meja <?= html_escape($no_meja); ?>
        </h5>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column">

        <?php if (!empty($cart)): ?>

            <div class="mb-3">

                <?php foreach ($cart as $cart_key => $item): ?>

                    <?php
                    $jumlah  = isset($item['jumlah']) ? (int)$item['jumlah'] : 0;

                    $nama_sambal_cart = trim((string) ($item['nama_sambal'] ?? ''));

                    $level_pedas_cart = isset($item['level_pedas'])
                        ? (int) $item['level_pedas']
                        : 0;
                    ?>

                    <div class="cart-line">

                        <div class="me-auto">
                            <div class="line-name">
                                <?= html_escape($item['nama_menu']); ?>
                            </div>

                            <div class="line-price">
                                <?= $jumlah; ?>x @ Rp
                                <?= number_format($item['harga'], 0, ',', '.'); ?>
                            </div>

                            <?php if ($nama_sambal_cart !== '' || $level_pedas_cart > 0): ?>

                                <div class="line-price">
                                    <?php if ($nama_sambal_cart !== ''): ?>
                                        Sambal: <?= html_escape($nama_sambal_cart); ?>
                                    <?php endif; ?>

                                    <?php if ($nama_sambal_cart !== '' && $level_pedas_cart > 0): ?>
                                        &middot;
                                    <?php endif; ?>

                                    <?php if ($level_pedas_cart > 0): ?>
                                        Level <?= $level_pedas_cart; ?>
                                    <?php endif; ?>
                                </div>

                            <?php endif; ?>
                        </div>

                        <div class="text-end">
                            <div class="line-sub money">
                                Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?>
                            </div>

                            <form action="<?= site_url('pelanggan/remove_cart/' . $no_meja); ?>"
                                  method="POST"
                                  class="d-inline">
                                <input type="hidden"
                                       name="<?= $this->security->get_csrf_token_name(); ?>"
                                       value="<?= $this->security->get_csrf_hash(); ?>">
                                <input type="hidden"
                                       name="cart_key"
                                       value="<?= html_escape((string) $cart_key); ?>">
                                <button type="submit"
                                        class="small text-danger border-0 bg-transparent p-0">
                                    <i class="fa-solid fa-trash-can me-1"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

            <!-- TOTAL -->
            <div class="sum-row is-total mb-3">
                <span>Total pembayaran</span>
                <span class="value money">
                    Rp <?= number_format($grand_total, 0, ',', '.'); ?>
                </span>
            </div>

            <!-- FORM CHECKOUT -->
            <form action="<?= site_url('pelanggan/checkout/' . $no_meja); ?>"
                  method="POST"
                  class="mt-4">

                <input type="hidden"
                       name="<?= $this->security->get_csrf_token_name(); ?>"
                       value="<?= $this->security->get_csrf_hash(); ?>">

                <div class="mb-3">
                    <label class="form-label" for="nama_pelanggan">Nama Anda</label>
                    <input type="text"
                           id="nama_pelanggan"
                           name="nama_pelanggan"
                           class="form-control"
                           required
                           autocomplete="name"
                           placeholder="Contoh: Andi">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="metode_pembayaran">Metode pembayaran</label>
                    <select id="metode_pembayaran"
                            name="metode_pembayaran"
                            class="form-select"
                            data-metode>
                        <option value="Cash">Tunai, bayar ke kasir</option>
                        <option value="QRIS">QRIS</option>
                    </select>
                </div>

                <!-- QRIS -->
                <div class="qris-panel mb-3"
                     data-qris-box
                     hidden>

                    <p class="qris-title mb-2">
                        <i class="fa-solid fa-qrcode me-1" aria-hidden="true"></i>
                        Scan QRIS warung
                    </p>

                    <div class="qris-frame">
                        <img src="<?= base_url('assets/images/qris-warung.png'); ?>"
                             alt="QRIS warung Ayam Geprek Romeesa"
                             width="878"
                             height="1200">
                    </div>

                    <p class="qris-help">
                        Buka aplikasi pembayaran atau m-banking, lalu pindai kode QRIS di atas. Setelah membayar, tunjukkan bukti pembayaran ke kasir.
                    </p>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="fa-solid fa-paper-plane me-2"></i>
                    Kirim pesanan
                </button>

            </form>

        <?php else: ?>

            <div class="empty-state">
                <i class="fa-solid fa-basket-shopping"></i>
                <h4>Keranjang masih kosong</h4>
                <p>Pilih menu dulu, lalu kembali ke sini untuk mengirim pesanan.</p>
            </div>

            <button type="button"
                    class="btn btn-primary w-100 mt-auto"
                    data-bs-dismiss="offcanvas">
                Pilih menu
            </button>

        <?php endif; ?>

    </div>
</aside>

<!-- SCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/theme.js'); ?>"></script>

<script>
(function () {
    document.querySelectorAll('[data-metode]').forEach(function (pilih) {
        var form = pilih.closest('form');
        var box = form ? form.querySelector('[data-qris-box]') : null;

        if (!box) return;

        function sinkron() {
            box.hidden = pilih.value !== 'QRIS';
        }

        pilih.addEventListener('change', sinkron);
        sinkron();
    });
})();
</script>

</body>
</html>
