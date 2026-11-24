
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$host_aktif = $_SERVER['HTTP_HOST'] ?? 'localhost';

$protokol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    ? 'https://'
    : 'http://';

$url_foto_dapur = $protokol . $host_aktif . '/ayam-geprek-romeesa-final/uploads/foto_menu/';

$total_pesanan = (isset($pesanan) && is_array($pesanan))
    ? count($pesanan)
    : 0;
?>

<!-- HEADER HALAMAN DAPUR -->
<div class="page-head">
    <div>
        <h2><?= $total_pesanan; ?> item pesanan aktif</h2>
        <p class="text-muted mb-0">
            Kelola pesanan masuk dan proses masakan hingga selesai.
        </p>
    </div>

    <span class="badge-soft is-ok">
        <span class="dot" aria-hidden="true"></span>
        Dapur aktif, diperbarui tiap 10 detik
    </span>
</div>

<!-- DAFTAR PESANAN -->
<?php if (!empty($pesanan) && is_array($pesanan)): ?>

    <div class="ticket-grid">

        <?php foreach ($pesanan as $p): ?>

            <?php
            $jumlah = isset($p['jumlah'])
                ? (int) $p['jumlah']
                : (isset($p['qty']) ? (int) $p['qty'] : 1);

            $status = strtolower(trim($p['status'] ?? 'pending'));

            $nama_foto = !empty($p['foto'])
                ? basename($p['foto'])
                : '';

            $id_detail = isset($p['id_detail'])
                ? (int) $p['id_detail']
                : 0;

            $nama_menu = $p['nama_menu'] ?? 'Menu tidak ditemukan';

            $nama_sambal = trim((string) ($p['nama_sambal'] ?? ''));

            $level_pedas = isset($p['level_pedas'])
                ? (int) $p['level_pedas']
                : 0;

            $nomor_meja = $p['nomor_meja'] ?? '-';

            $no_faktur = $p['no_faktur'] ?? '-';

            $waktu_pesanan = !empty($p['tanggal'])
                ? date('H:i', strtotime($p['tanggal']))
                : '-';

            // INFORMASI STATUS
            if ($status === 'pending') {
                $label_status = 'Menunggu dimasak';
                $badge_status = 'is-warn';
                $ikon_status = 'fa-hourglass-half';
            } elseif ($status === 'proses' || $status === 'diproses') {
                $label_status = 'Sedang dimasak';
                $badge_status = 'is-ok';
                $ikon_status = 'fa-fire';
            } elseif ($status === 'selesai') {
                $label_status = 'Selesai';
                $badge_status = 'is-ok';
                $ikon_status = 'fa-circle-check';
            } else {
                $label_status = ucfirst($status);
                $badge_status = 'is-warn';
                $ikon_status = 'fa-clock';
            }
            ?>

            <article class="ticket">

                <!-- HEADER TIKET -->
                <div class="ticket-head">

                    <span class="ticket-num">
                        #<?= html_escape($no_faktur); ?>
                    </span>

                    <span class="badge-soft is-warn">
                        <i class="fa-solid fa-chair" aria-hidden="true"></i>
                        <?= html_escape($nomor_meja); ?>
                    </span>

                </div>

                <!-- BODY TIKET -->
                <div class="ticket-body">

                    <div class="ticket-item">

                        <!-- FOTO MENU -->
                        <?php if (!empty($nama_foto)): ?>

                            <img
                                class="ticket-thumb"
                                src="<?= html_escape($url_foto_dapur . rawurlencode($nama_foto)); ?>"
                                alt="<?= html_escape($nama_menu); ?>"
                                loading="lazy"
                            >

                        <?php else: ?>

                            <span class="ticket-thumb is-empty">
                                <i class="fa-solid fa-utensils" aria-hidden="true"></i>
                            </span>

                        <?php endif; ?>

                        <!-- INFORMASI MENU -->
                        <div class="me-auto">

                            <div class="fw-bold">
                                <?= html_escape($nama_menu); ?>
                            </div>

                            <?php if ($nama_sambal !== '' || $level_pedas > 0): ?>
                                <div class="text-muted small">
                                    <?php if ($nama_sambal !== ''): ?>
                                        <i class="fa-solid fa-pepper-hot me-1" aria-hidden="true"></i>
                                        <?= html_escape($nama_sambal); ?>
                                    <?php endif; ?>

                                    <?php if ($nama_sambal !== '' && $level_pedas > 0): ?>
                                        &middot;
                                    <?php endif; ?>

                                    <?php if ($level_pedas > 0): ?>
                                        Level <?= $level_pedas; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <div class="text-muted small">
                                <i class="fa-regular fa-clock me-1" aria-hidden="true"></i>
                                <?= html_escape($waktu_pesanan); ?> WIB
                            </div>

                        </div>

                        <!-- JUMLAH -->
                        <span class="qty-badge">
                            x<?= $jumlah; ?>
                        </span>

                    </div>

                    <!-- STATUS MASAK -->
                    <div class="mt-3">

                        <span class="badge-soft <?= $badge_status; ?>">
                            <i class="fa-solid <?= $ikon_status; ?> me-1" aria-hidden="true"></i>
                            <?= html_escape($label_status); ?>
                        </span>

                    </div>

                </div>

                <!-- FOOTER / TOMBOL AKSI -->
                <div class="ticket-foot">

                    <?php if ($status === 'pending' && $id_detail > 0): ?>

                        <form action="<?= site_url('kitchen/mulai/' . $id_detail); ?>" method="POST">
                            <input type="hidden"
                                   name="<?= $this->security->get_csrf_token_name(); ?>"
                                   value="<?= $this->security->get_csrf_hash(); ?>">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa-solid fa-fire me-2" aria-hidden="true"></i>
                                Mulai Masak
                            </button>
                        </form>

                    <?php elseif (($status === 'proses' || $status === 'diproses') && $id_detail > 0): ?>

                        <form action="<?= site_url('kitchen/selesai/' . $id_detail); ?>" method="POST">
                            <input type="hidden"
                                   name="<?= $this->security->get_csrf_token_name(); ?>"
                                   value="<?= $this->security->get_csrf_hash(); ?>">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fa-solid fa-circle-check me-2" aria-hidden="true"></i>
                                Selesaikan Masakan
                            </button>
                        </form>

                    <?php elseif ($status === 'selesai'): ?>

                        <button
                            type="button"
                            class="btn btn-secondary w-100"
                            disabled
                        >
                            <i class="fa-solid fa-check me-2" aria-hidden="true"></i>
                            Pesanan Selesai
                        </button>

                    <?php else: ?>

                        <button
                            type="button"
                            class="btn btn-secondary w-100"
                            disabled
                        >
                            Status pesanan tidak dikenali
                        </button>

                    <?php endif; ?>

                </div>

            </article>

        <?php endforeach; ?>

    </div>

<?php else: ?>

    <!-- ANTREAN KOSONG -->
    <div class="card">

        <div class="empty-state">

            <i class="fa-solid fa-mug-hot" aria-hidden="true"></i>

            <h4>Dapur sedang kosong</h4>

            <p>
                Belum ada pesanan yang masuk ke dapur.
                Pesanan baru akan muncul otomatis tanpa perlu menyegarkan halaman secara manual.
            </p>

        </div>

    </div>

<?php endif; ?>
