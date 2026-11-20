<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$cart        = $this->session->userdata('cart_pelanggan');
$grand_total = 0;
$total_qty   = 0;
if (!empty($cart)) {
    foreach ($cart as $item) {
        $grand_total += $item['subtotal'];
        $total_qty   += $item['qty'];
    }
}

$label_kategori = ['makanan' => 'Makanan', 'minuman' => 'Minuman', 'topping' => 'Topping'];
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
            <span class="brand-mark"><i class="fa-solid fa-utensils" aria-hidden="true"></i></span>
            <span class="brand-text">
                <strong>Geprek Romeesa</strong>
                <small>Pesan langsung dari meja</small>
            </span>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="table-chip">
                <i class="fa-solid fa-chair" aria-hidden="true"></i> Meja <?= html_escape($no_meja); ?>
            </span>

            <button type="button" class="icon-btn icon-btn-dark position-relative"
                    data-bs-toggle="offcanvas" data-bs-target="#cartCanvas" aria-controls="cartCanvas">
                <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
                <span class="visually-hidden">Buka keranjang pesanan</span>
                <?php if ($total_qty > 0): ?><span class="cart-badge"><?= $total_qty; ?></span><?php endif; ?>
            </button>
        </div>
    </div>
</header>

<div class="customer-wrap" data-filter>

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

    <div class="section-head">
        <div>
            <h3>Menu tersedia</h3>
            <p class="small text-muted mb-0">
                <span data-filter-count><?= is_array($menu) ? count($menu) : 0; ?></span> pilihan menu
            </p>
        </div>

        <div class="topbar-search" style="width: 240px; max-width: 100%;">
            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
            <input type="search" data-filter-search placeholder="Cari menu" aria-label="Cari menu">
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
                <?php $habis = (isset($m['status']) && $m['status'] === 'habis'); ?>
                <article class="dish<?= $habis ? ' is-habis' : ''; ?>"
                         data-filter-item
                         data-category="<?= html_escape(isset($m['kategori']) ? $m['kategori'] : ''); ?>"
                         data-name="<?= html_escape(strtolower($m['nama_menu'])); ?>">

                    <div class="dish-media">
                        <?php if (!empty($m['foto'])): ?>
                            <img src="<?= base_url('uploads/' . $m['foto']); ?>" alt="<?= html_escape($m['nama_menu']); ?>">
                        <?php endif; ?>
                        <span class="dish-tag">
                            <?= isset($label_kategori[$m['kategori']]) ? $label_kategori[$m['kategori']] : 'Menu'; ?>
                        </span>
                    </div>

                    <div class="dish-body">
                        <?php if ($habis): ?>
                            <span class="badge-soft is-danger align-self-start">Sedang habis</span>
                        <?php endif; ?>

                        <span class="dish-name"><?= html_escape($m['nama_menu']); ?></span>

                        <div class="dish-foot">
                            <span class="dish-price">Rp <?= number_format($m['harga'], 0, ',', '.'); ?></span>

                            <?php if ($habis): ?>
                                <button type="button" class="dish-add" disabled aria-label="<?= html_escape($m['nama_menu']); ?> sedang habis">
                                    <i class="fa-solid fa-ban" aria-hidden="true"></i>
                                </button>
                            <?php else: ?>
                                <a class="dish-add" href="<?= site_url('pelanggan/add_cart/' . $m['id'] . '/' . $no_meja); ?>"
                                   aria-label="Tambah <?= html_escape($m['nama_menu']); ?> ke keranjang">
                                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                                </a>
                            <?php endif; ?>
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
        <div class="card">
            <div class="empty-state">
                <i class="fa-solid fa-box-open" aria-hidden="true"></i>
                <h4>Belum ada menu tersedia</h4>
                <p>Menu sedang diperbarui. Silakan panggil petugas kami untuk sementara ini.</p>
            </div>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="<?= site_url('pelanggan/cek_status_aktif/' . $no_meja); ?>" class="btn btn-light">
            <i class="fa-solid fa-clock-rotate-left me-2" aria-hidden="true"></i> Cek status pesanan meja ini
        </a>
    </div>
</div>

<?php if ($total_qty > 0): ?>
    <div class="floating-cart d-lg-none">
        <div class="inner">
            <div>
                <span class="badge-soft is-brand"><?= $total_qty; ?> item</span>
                <div class="fw-extrabold money">Rp <?= number_format($grand_total, 0, ',', '.'); ?></div>
            </div>
            <button type="button" class="btn btn-primary ms-auto"
                    data-bs-toggle="offcanvas" data-bs-target="#cartCanvas" aria-controls="cartCanvas">
                <i class="fa-solid fa-cart-shopping me-2" aria-hidden="true"></i> Lihat keranjang
            </button>
        </div>
    </div>
<?php endif; ?>

<aside class="offcanvas offcanvas-end" tabindex="-1" id="cartCanvas" aria-labelledby="cartCanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="cartCanvasLabel">
            Keranjang meja <?= html_escape($no_meja); ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column">
        <?php if (!empty($cart)): ?>
            <div class="mb-3">
                <?php foreach ($cart as $item): ?>
                    <div class="cart-line">
                        <div class="me-auto">
                            <div class="line-name"><?= html_escape($item['nama_menu']); ?></div>
                            <div class="line-price"><?= $item['qty']; ?>x @ Rp <?= number_format($item['harga'], 0, ',', '.'); ?></div>
                        </div>
                        <div class="text-end">
                            <div class="line-sub money">Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></div>
                            <a class="small text-danger" href="<?= site_url('pelanggan/remove_cart/' . $item['id'] . '/' . $no_meja); ?>"
                               aria-label="Hapus <?= html_escape($item['nama_menu']); ?> dari keranjang">
                                <i class="fa-solid fa-trash-can me-1" aria-hidden="true"></i>Hapus
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="sum-row is-total mb-3">
                <span>Total pembayaran</span>
                <span class="value money">Rp <?= number_format($grand_total, 0, ',', '.'); ?></span>
            </div>

            <form action="<?= site_url('pelanggan/checkout/' . $no_meja); ?>" method="POST" class="mt-4">
                <div class="mb-3">
                    <label class="form-label" for="nama_pemesan">Nama Anda</label>
                    <input type="text" id="nama_pemesan" name="nama_pemesan" class="form-control" required
                           autocomplete="name" placeholder="Contoh: Andi">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="metode_pembayaran">Metode pembayaran</label>
                    <select id="metode_pembayaran" name="metode_pembayaran" class="form-select" data-metode>
                        <option value="Cash">Tunai, bayar ke kasir</option>
                        <option value="QRIS">QRIS, langsung diproses</option>
                    </select>
                </div>

                <div class="mb-3 text-center p-3" data-qris-box hidden
                     style="background: var(--surface-2); border: 1px solid var(--border); border-radius: var(--r-md);">
                    <p class="fw-bold small mb-2">
                        <i class="fa-solid fa-qrcode me-1" aria-hidden="true"></i> Scan QRIS berikut
                    </p>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&amp;data=AYAM-GEPREK-ROMEESA-MEJA-<?= urlencode($no_meja); ?>"
                         alt="Kode QRIS untuk meja <?= html_escape($no_meja); ?>"
                         width="150" height="150" class="rounded-3 bg-white p-2 border">
                    <small class="text-muted d-block mt-2">
                        Bisa dibayar lewat GoPay, OVO, DANA, atau m-banking.
                    </small>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="fa-solid fa-paper-plane me-2" aria-hidden="true"></i> Kirim pesanan
                </button>
            </form>
        <?php else: ?>
            <div class="empty-state">
                <i class="fa-solid fa-basket-shopping" aria-hidden="true"></i>
                <h4>Keranjang masih kosong</h4>
                <p>Pilih menu dulu, lalu kembali ke sini untuk mengirim pesanan.</p>
            </div>
            <button type="button" class="btn btn-primary w-100 mt-auto" data-bs-dismiss="offcanvas">
                Pilih menu
            </button>
        <?php endif; ?>
    </div>
</aside>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/theme.js'); ?>"></script>
<script>
    /* Tampilkan kode QRIS hanya saat metode pembayaran QRIS dipilih */
    (function () {
        Array.prototype.forEach.call(document.querySelectorAll('[data-metode]'), function (pilih) {
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
